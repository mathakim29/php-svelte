<script>
  // Import directly from esm.sh inside the .svelte file (this is example)
  import confetti from 'canvas-confetti';

  export function fire() {
    confetti({
      particleCount: 50,
      spread: 60,
      origin: { y: 0.8 }
    });
  }
</script>

<button onclick={fire}>
  🎉 Fire Confetti!s
</button>

<style>
  button {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    cursor: pointer;
  }
</style>