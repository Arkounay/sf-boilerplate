import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['input', 'count']

    static values = {
        currentCharCount: Number,
        maxCharCount: Number
    }

    connect() {
        this.updateText();
    }

    updateText() {
        this.currentCharCountValue = this.inputTarget.value.length;
    }

    currentCharCountValueChanged() {
        if (this.currentCharCountValue > this.maxCharCountValue * 2) {
            this.countTarget.classList.add('text-danger');
            this.inputTarget.classList.add('text-danger');
        } else if (this.currentCharCountValue > this.maxCharCountValue * 1.2) {
            this.countTarget.classList.add('text-warning');
            this.inputTarget.classList.add('text-warning');
            this.countTarget.classList.remove('text-danger');
            this.inputTarget.classList.remove('text-danger');
        } else {
            this.countTarget.classList.remove('text-warning');
            this.inputTarget.classList.remove('text-warning');
        }
        if (this.currentCharCountValue === 0) {
            this.countTarget.classList.add('d-none');
            this.inputTarget.classList.add('mb-3-5');
        } else {
            this.countTarget.classList.remove('d-none');
            this.inputTarget.classList.remove('mb-3-5');
        }

        this.countTarget.innerHTML = this.currentCharCountValue + '/' + this.maxCharCountValue;
    }

}
