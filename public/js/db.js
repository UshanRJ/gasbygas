// Initialize IndexedDB
const dbPromise = idb.openDB('app-store', 1, {
    upgrade(db) {
      // Create object stores for your data
      db.createObjectStore('keyval');
      // Add more stores as needed for your app
      db.createObjectStore('userData', { keyPath: 'id' });
    },
  });
  
  // Helper functions for working with IndexedDB
  const idbHelpers = {
    async get(key) {
      return (await dbPromise).get('keyval', key);
    },
    async set(key, val) {
      return (await dbPromise).put('keyval', val, key);
    },
    async delete(key) {
      return (await dbPromise).delete('keyval', key);
    },
    async clear() {
      return (await dbPromise).clear('keyval');
    },
    async keys() {
      return (await dbPromise).getAllKeys('keyval');
    },
    // User data specific functions
    async saveUserData(data) {
      return (await dbPromise).put('userData', data);
    },
    async getUserData(id) {
      return (await dbPromise).get('userData', id);
    },
    async getAllUserData() {
      return (await dbPromise).getAll('userData');
    }
  };
  
  // Sync data with server when online
  window.addEventListener('online', async () => {
    const pendingData = await idbHelpers.getAllUserData();
    // Process pending data and sync with server
    for (const item of pendingData) {
      if (item.needsSync) {
        try {
          const response = await fetch('/api/sync', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(item)
          });
          
          if (response.ok) {
            // Update the synced flag
            item.needsSync = false;
            await idbHelpers.saveUserData(item);
          }
        } catch (error) {
          console.error('Sync failed:', error);
        }
      }
    }
  });
  
  // Add to window for easy access
  window.idbHelpers = idbHelpers;