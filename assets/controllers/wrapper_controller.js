import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
	connect() {
		let main = document.querySelector("main")
		let mainHeight = main.offsetHeight
		let header = document.querySelector("header")
		let headerHeight = header.offsetHeight
		let height = this.element.offsetHeight
		let diff = mainHeight + headerHeight - height
		this.element.style.setProperty("--rest-height", diff + "px")
	}
}
