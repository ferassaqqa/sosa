/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyBSi08DoXXU5rNvK7chgObzLi_l1_807VM",
    authDomain: "sunna-b0909.firebaseapp.com",
    projectId: "sunna-b0909",
    storageBucket: "sunna-b0909.appspot.com",
    messagingSenderId: "141645051731",
    appId: "1:141645051731:web:11c4995110408f7a4993a6",
    measurementId: "G-D7XE883R18"
});
/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    // console.log(
    //     "[firebase-messaging-sw.js] Received background message ",
    //     payload
    // );
    /* Customize notification here */
    // const notificationTitle = "Background Message Title";
    // const notificationOptions = {
    //     body: "Background Message body.",
    //     icon: "/itwonders-web-logo.png",
    // };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});