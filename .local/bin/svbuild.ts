import * as esbuild from "npm:esbuild";
import sveltePlugin from "npm:esbuild-svelte";
import { resolve, dirname, basename } from "jsr:@std/path";

const target = resolve(Deno.args[0] ?? "app/App.svelte");
const outDir = resolve(Deno.args[1] ?? "dist");
const stat = await Deno.stat(target);
const appDir = stat.isDirectory ? target : dirname(target);
const appFile = stat.isDirectory ? "App.svelte" : basename(target);

try {
  const packageList = await Deno.readTextFile(resolve(appDir, "build.txt"));
  for (const line of packageList.split(/\r?\n/)) {
    const specifier = line.trim();
    if (specifier && !specifier.startsWith("#")) await import(specifier);
  }
} catch (error) {
  if (!(error instanceof Deno.errors.NotFound)) throw error;
}

await Deno.mkdir(outDir, { recursive: true });

const ctx = await esbuild.context({
  stdin: {
    contents: `import { mount } from 'svelte'; import App from './${appFile}'; mount(App, { target: document.body });`,
    loader: "js",
    resolveDir: appDir,
  },
  bundle: true,
  format: "esm",
  outfile: `${outDir}/bundle.js`,
  sourcemap: true,
  conditions: ["svelte", "browser"],
  plugins: [sveltePlugin({ compilerOptions: { css: "injected" } })],
  logLevel: "info",
});

await ctx.rebuild();
await ctx.dispose();
