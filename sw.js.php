<?php
include_once('app/config/config.php');
header("Content-type: text/javascript; charset: UTF-8");
?>
'use strict';

const applicationServerPublicKey = "<?=SERVER_KEYS['public_key']?>";
var OFFLINE_CACHE = 'offline';
var OFFLINE_URL = 'html/offline.html';

const CACHE = "Alex<?=SITE_VERSION?>";
var precacheFiles = [
	'assets/fonts/raleway.css',
	'assets/fonts/files/hinted-Raleway-Medium.eot',
	'assets/fonts/files/hinted-Raleway-Medium.ttf',
	'assets/fonts/files/hinted-Raleway-Medium.woff',
	'assets/fonts/files/hinted-Raleway-Medium.woff2',
	'assets/fonts/files/hinted-Raleway-Bold.eot',
	'assets/fonts/files/hinted-Raleway-Bold.ttf',
	'assets/fonts/files/hinted-Raleway-Bold.woff',
	'assets/fonts/files/hinted-Raleway-Bold.woff2',

	'assets/material-icons/material-icons.css',
	'assets/material-icons/MaterialIcons-Regular.eot',
	'assets/material-icons/MaterialIcons-Regular.ijmap',
	'assets/material-icons/MaterialIcons-Regular.svg',
	'assets/material-icons/MaterialIcons-Regular.ttf',
	'assets/material-icons/MaterialIcons-Regular.woff',
	'assets/material-icons/MaterialIcons-Regular.woff2',

	'assets/css/shell.css',
	'assets/js/shell.js',
];

// On install, cache some resource.
self.addEventListener('install', function(evt) {
  evt.waitUntil(caches.open(CACHE).then(function (cache) {
    cache.addAll(precacheFiles);
  }));
}, {passive: true});


self.addEventListener('fetch', function(event) {

  if (event.request.method === 'GET'){
    event.respondWith(
      caches.match(event.request).then(function(resp) {
        return resp || fetch(event.request).then(function(response) {
          return caches.open(CACHE).then(function(cache) {

            cache.put(event.request, response.clone());
            return response;
          });
        });
      })
    );
  }else if (event.request.mode === 'navigate' ||
      (event.request.method === 'GET' &&
      event.request.headers.get('accept').includes('text/html'))) {

      // a GET request should not have any body (blob), but sometimes it does
      event.waitUntil(event.request.blob().then(function(blob) {
          if (blob.size == 0) {
              return event.respondWith(fetch(event.request).catch(function () {}));
          }
      }));
  }
}, {passive: true});

self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.filter(function(cacheName) {
        }).map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    })
  );
}, {passive: true});

function fromCache(request) {
  return caches.open(CACHE).then(function (cache) {
    return cache.match(request);
  });
}

function update(request) {
  return caches.open(CACHE).then(function (cache) {
    return fetch(request).then(function (response) {
      return cache.put(request, response.clone()).then(function () {
        return response;
      });
    });
  });
}

// Sends a message to the clients.
function refresh(response) {
  return self.clients.matchAll().then(function (clients) {
    clients.forEach(function (client) {
      var message = {
        type: 'refresh',
        url: response.url,
        eTag: response.headers.get('ETag')
      };
      client.postMessage(JSON.stringify(message));
    });
  });
}

self.addEventListener('push', (event) => {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }


    let sendNotification = (data) => {
        //console.log(data.json());
        // on actualise la page des notifications ou/et le compteur de notifications
        self.refreshNotifications();
        var title = data.title || 'Notificación de Alejandro Jaime';
        var options = {
          body: data.body || 'Esta es una notificación de prueba desde mi web!',
          icon: data.icon || 'assets/favicon/android-chrome-192x192.png',
          badge: data.badge || 'assets/images/shell/campana.svg',
          tag : data.tag || 'general',
          dir : data.dir || 'auto',
          vibrate: data.vibrate || [500,110,500,110,450,110,200,110,170,40,450,110,200,110,170,40,500],
        };

        return self.registration.showNotification(title, options);
    };

    if (event.data) {

        event.waitUntil(
            sendNotification(event.data.json())
        );
    } else {
        event.waitUntil(
            self.registration.pushManager.getSubscription().then((subscription) => {
                if (!subscription) {
                    return;
                }

                return fetch('api/notifications/last?endpoint=' + encodeURIComponent(subscription.endpoint)).then((response) => {
                    if (response.status !== 200) {
                        throw new Error();
                    }

                    // Examine the text in the response
                    return response.json().then((data) => {
                        if (data.error || !data.notification) {
                            throw new Error();
                        }

                        return sendNotification(data.notification.message);
                    });
                }).catch(() => {
                    return sendNotification();
                });
            })
        );
    }
});

self.refreshNotifications = (clientList) => {
    if (clientList == undefined) {
        clients.matchAll({ type: "window" }).then( (clientList) => {
            self.refreshNotifications(clientList);
        });
    } else {
        for (let i = 0; i < clientList.length; i++) {
            let client = clientList[i];
            if (client.url.search(/notifications/i) >= 0) {
                // si la page des notifications est ouverte on la recharge
                client.postMessage('reload');
            }

            // si on n'est pas sur la page des notifications on recharge le compteur
            client.postMessage('refreshNotifications');
        }
    }
};

self.addEventListener('notificationclick', (event) => {
    // fix http://crbug.com/463146
    event.notification.close();

    event.waitUntil(
        clients.matchAll({
            type: "window"
        })
        .then( (clientList) => {
            // si la page des notifications est ouverte on l'affiche en priorité
            for (let i = 0; i < clientList.length; i++) {
                let client = clientList[i];
                if (client.url.search(/notifications/i) >= 0 && 'focus' in client) {
                    return client.focus();
                }
            }

            // sinon s'il y a quand même une page du site ouverte on l'affiche
            if (clientList.length && 'focus' in client) {
                return client.focus();
            }

            // sinon on ouvre la page des notifications
            if (clients.openWindow) {
                return clients.openWindow('notifications');
            }
        })
    );
});

self.addEventListener('message', (event) => {
    var message = event.data;

    switch (message) {
        case 'dispatchRemoveNotifications':
            clients.matchAll({ type: "window" }).then( (clientList) => {
                for (let i = 0; i < clientList.length; i++) {
                    clientList[i].postMessage('removeNotifications');
                }
            });
            break;
        default:
            break;
    }
});
