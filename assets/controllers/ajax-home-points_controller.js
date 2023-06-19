import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["points"];
    static values = {
        path: String,
    };

    async connect() {
        try {
            fetch(document.getElementById('pointOutput').dataset.url)
                .then((resp) => resp.json())
                .then((data) => (document.getElementById('pointOutput').textContent = data));
        } catch (e) {
            console.error(e);
        }
        setInterval(this.connect, 119000);
    }
}
