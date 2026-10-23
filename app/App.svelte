<script>
  import ConfettiButton from './confetti.svelte';

  let count = $state(0);
</script>

<main>
  <h1>Svelte + esm.sh Example</h1>
  <p>Clicks: {count}</p>
  
  <button onclick={() => count++}>
    Increment Count
  </button>

  <hr />

  <!-- Render the confetti component -->
  <ConfettiButton />
</main>

<style>
  main {
    text-align: center;
    font-family: sans-serif;
    margin-top: 3rem;
  }
  button {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    cursor: pointer;
    margin-bottom: 1rem;
  }
  hr {
    margin: 2rem auto;
    width: 50%;
  }
</style>