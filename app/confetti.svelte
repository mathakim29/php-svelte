<script>
  // Import directly from esm.sh inside the .svelte file (this is example)
  import confetti from 'https://esm.sh/canvas-confetti@1.9.4';

  export function fire() {
    confetti({
      particleCount: 50,
      spread: 60,
      origin: { y: 0.8 }
    });
  }
</script>

<button onclick={fire}>
  🎉 Fire Confetti!
</button>

<style>
  button {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    cursor: pointer;
  }
</style>