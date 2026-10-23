import * as esbuild from 'npm:esbuild';
import sveltePlugin from 'npm:esbuild-svelte';

const APP_DIR = '/app/app';
const OUT_DIR = '/app/public';
const TAILWIND_INPUT = '@import "tailwindcss"; @source "/app/app";';

// Persistent esbuild context: incremental rebuilds, no re-import cost
const ctx = await esbuild.context({
  stdin: {
    contents: `import { mount } from 'svelte'; import App from './App.svelte'; mount(App, { target: document.body });`,
    loader: 'js',
    resolveDir: APP_DIR,
  },
  bundle: true,
  format: 'esm',
  outfile: `${OUT_DIR}/bundle.js`,
  conditions: ['svelte', 'browser'],
  plugins: [sveltePlugin({ compilerOptions: { css: 'injected' } })],
  logLevel: 'error',
});

async function buildCss() {
  const proc = new Deno.Command(Deno.execPath(), {
    args: ['run', '-A', 'npm:@tailwindcss/cli', '-i', '-', '-o', `${OUT_DIR}/style.css`],
    stdin: 'piped',
    stdout: 'inherit',
    stderr: 'inherit',
  }).spawn();
  const writer = proc.stdin.getWriter();
  await writer.write(new TextEncoder().encode(TAILWIND_INPUT));
  await writer.close();
  const { success } = await proc.status;
  if (!success) console.error('Tailwind build failed');
}

async function rebuild() {
  const t = performance.now();
  try {
    await Promise.all([buildCss(), ctx.rebuild()]); // run in parallel
    console.log(`Rebuilt in ${(performance.now() - t).toFixed(0)}ms`);
  } catch (e) {
    console.error('Build error:', e instanceof Error ? e.message : e);
  }
}

// Debounce + serialize: at most one build running, one queued
let timer: number | undefined;
let building = false;
let queued = false;

function schedule() {
  clearTimeout(timer);
  timer = setTimeout(run, 100);
}

async function run() {
  if (building) { queued = true; return; }
  building = true;
  do {
    queued = false;
    await rebuild();
  } while (queued);
  building = false;
}

// Clean shutdown
const shutdown = async () => { await ctx.dispose(); Deno.exit(0); };
Deno.addSignalListener('SIGINT', shutdown);
Deno.addSignalListener('SIGTERM', shutdown);

await rebuild();
for await (const event of Deno.watchFs(APP_DIR, { recursive: true })) {
  if (['modify', 'create', 'remove'].includes(event.kind)) schedule();
}