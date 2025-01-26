import { usePage } from "@inertiajs/vue3";

export function httpGet(url){
    return fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    }).then(response => response.json()).catch(error => {
        // Handle errors here (e.g., log to console, show message to user)
        console.error('Fetch error:', error);
    });
}
