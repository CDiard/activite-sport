import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
	connect() {
		let child = this.element.querySelector("ul")
		let contentHeight = child.offsetHeight
		this.element.style.maxHeight = "calc(" + contentHeight + "px + var(--item-margin-top))"
		console.log(this.element.style.height)
	}
}
