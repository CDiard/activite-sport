import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["points"];
    static values = {
        path: String,
    };

    async connect() {
        try {
            fetch(this.pathValue)
                .then((resp) => resp.json())
                .then((data) => (this.pointsTarget.textContent = data));
        } catch (e) {
            console.error(e);
        }
    }
}
