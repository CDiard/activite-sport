import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {

        let retirer = this.element.getElementsByTagName('span')[0];
        let augmenter = this.element.getElementsByTagName('span')[1];
        let valeur = document.getElementById('coefficient-epreuve');
        // setAttribute
        console.log(retirer);
        console.log(augmenter);
        console.log(valeur);

        retirer.addEventListener('click', function() {
            if (valeur.value > 0) {
                valeur.value = parseInt(valeur.value) - 1;
            }
            
        });

        augmenter.addEventListener('click', function() {
            valeur.value = parseInt(valeur.value) + 1;
        });

    }
}
