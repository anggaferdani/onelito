<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bersihkan Cache</title>
</head>
<body>
  <p>Sedang membersihkan data browser...</p>

  <script>
    (async () => {
      try {
        localStorage.clear();
        sessionStorage.clear();

        if ('indexedDB' in window) {
          let dbs = await indexedDB.databases();
          for (const db of dbs) {
            if (db.name) indexedDB.deleteDatabase(db.name);
          }
        }

        if ('caches' in window) {
          const names = await caches.keys();
          for (const name of names) {
            await caches.delete(name);
          }
        }

        document.cookie.split(";").forEach(c => {
          document.cookie = c.replace(/^ +/, "")
            .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });

        setTimeout(() => {
          location.href = '/';
        }, 500);
      } catch (e) {
        alert("Gagal membersihkan cache.");
        console.error(e);
        location.href = '/';
      }
    })();
  </script>
</body>
</html>
