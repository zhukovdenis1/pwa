<button id="installApp">install</button>
<script type="text/javascript">
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        deferredPrompt = e;
    });

    const installApp = document.getElementById('installApp');

    installApp.addEventListener('click', async () => {
        console.log(deferredPrompt);
        if (deferredPrompt !== null) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                deferredPrompt = null;
            }
        }
    });

</script>