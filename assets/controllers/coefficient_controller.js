import { Controller } from '@hotwired/stimulus';

console.log('ok');

export default class extends Controller {
    connect() {

        let retirer = this.element.getElementsByTagName('span')[0];
        let augmenter = this.element.getElementsByTagName('span')[1];
        let valeur = this.element.getElementsByTagName('input')[0];
        let valeur2 = '';
        if (document.getElementById('coefficient-epreuve-2')){
            valeur2 = this.element.getElementsByTagName('input');
        }
        // setAttribute
        console.log(retirer);
        console.log(augmenter);
        console.log(valeur);

        retirer.addEventListener('click', function() {
            if (valeur.value > 0) {
                valeur.value = parseInt(valeur.value) - 1;
            }
            if (valeur2.value > 0 && valeur2.value != null) {
                valeur2.value = parseInt(valeur.value) - 1;
            }
            
        });

        augmenter.addEventListener('click', function() {
            valeur.value = parseInt(valeur.value) + 1;
            if (valeur2.value != null) {
                valeur2.value = parseInt(valeur.value) + 1;
            }
        });

    }
}
