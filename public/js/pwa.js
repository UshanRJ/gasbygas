// Register service worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
  });
}

// Handle install prompt
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  console.log('Install prompt triggered!');
  
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault();
  
  // Stash the event so it can be triggered later
  deferredPrompt = e;
  
  // Show the install button
  const installButton = document.getElementById('pwa-install-button');
  if (installButton) {
    installButton.style.display = 'block';
    
    // Remove any existing click listeners to prevent duplicates
    const newButton = installButton.cloneNode(true);
    installButton.parentNode.replaceChild(newButton, installButton);
    
    // Add the click listener to the new button
    newButton.addEventListener('click', () => {
      console.log('Install button clicked');
      // Hide the button
      newButton.style.display = 'none';
      
      // Show the prompt
      if (deferredPrompt) {
        console.log('Showing install prompt');
        deferredPrompt.prompt();
        
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
          console.log('User choice result:', choiceResult.outcome);
          if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the install prompt');
          } else {
            console.log('User dismissed the install prompt');
            // Maybe show the button again
            newButton.style.display = 'block';
          }
          deferredPrompt = null;
        });
      } else {
        console.log('No deferred prompt available');
      }
    });
  } else {
    console.log('Install button not found in the DOM');
  }
});

// Add event listener for when the PWA is successfully installed
window.addEventListener('appinstalled', (e) => {
  console.log('PWA has been installed');
  
  // Hide the install button after installation
  const installButton = document.getElementById('pwa-install-button');
  if (installButton) {
    installButton.style.display = 'none';
  }
});