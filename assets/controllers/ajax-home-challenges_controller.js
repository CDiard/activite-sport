import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["output"];
    static values = {
        path: String,
    };

    async connect() {
        let direction = document.createElement("p");
        direction.className = "direction";
        let description = document.createElement("p");
        description.className = "description";
        console.log(this.outputTarget);
        this.outputTarget.innerHTML = ``;
        try {
            await fetch(this.pathValue)
                .then((resp) => resp.json())
                .then((data) => {
                    data.map((item) => {
                        let directionClone = direction.cloneNode(true);
                        let descriptionClone = description.cloneNode(true);
                        directionClone.textContent = item.name;
                        descriptionClone.textContent = item.description;
                        this.outputTarget.appendChild(directionClone);
                        this.outputTarget.appendChild(descriptionClone);
                        return;
                    });
                });
        } catch (e) {
            console.error(e);
        }
        setTimeout(this.connect, 10000);
    }
}
