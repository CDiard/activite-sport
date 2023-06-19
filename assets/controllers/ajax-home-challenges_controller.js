import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["output"];
    static values = {
        path: String,
    };
    static pathDef;

    async connect() {
        let direction = document.createElement("p");
        direction.className = "direction";
        let description = document.createElement("p");
        description.className = "description";
        document.getElementById('listChallenges').innerHTML = ``;
        try {
            await fetch(document.getElementById('listChallenges').dataset.url)
                .then((resp) => resp.json())
                .then((data) => {
                    data.map((item) => {
                        let directionClone = direction.cloneNode(true);
                        let descriptionClone = description.cloneNode(true);
                        directionClone.textContent = item.name;
                        descriptionClone.textContent = item.description;
                        document.getElementById('listChallenges').appendChild(directionClone);
                        document.getElementById('listChallenges').appendChild(descriptionClone);
                        return;
                    });
                });
        } catch (e) {
            console.error(e);
        }
        setInterval(this.connect, 120000);
    }
}
