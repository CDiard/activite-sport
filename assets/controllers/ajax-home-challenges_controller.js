import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["output"];
    static values = {
        path: String
    }

    async connect() {
        let direction = document.createElement('p');
        direction.className = "direction";
        let description = document.createElement('p');
        description.className = "description";
        this.outputTarget.textContent = ``;
        console.log(this.element, this)
        console.log(this.pathValue)
        console.log(this.outputTarget)
        try {
            fetch(this.pathValue).then(resp => resp.json()).then(data => {
                data.map((item) => {
                    console.log(item);
                    direction.textContent = item.name;
                    description.textContent = item.description;
                    this.outputTarget.appendChild(direction);
                    this.outputTarget.appendChild(description);
                    return;
                })
            })
        } catch (e) {
            console.error(e)
        }


    }
}
