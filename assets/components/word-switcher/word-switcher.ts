import Component from '@wexample/symfony-loader/js/Class/Component';

// How often the letters change while the word rolls.
const WORD_SWITCHER_TICK = 30;

const WORD_SWITCHER_LETTERS = 'abcdefghijklmnopqrstuvwxyz0123456789';

export default class extends Component {
  private wordEl!: HTMLElement;

  private cursor = 0;

  private intervalTimer?: number;

  private rollTimer?: number;

  attachHtmlElements() {
    super.attachHtmlElements();

    this.wordEl = this.el.querySelector('.word-switcher--word') as HTMLElement;
  }

  protected async activateListeners(): Promise<void> {
    await super.activateListeners();

    const words: string[] = this.options.words ?? [];

    if (words.length < 2) {
      return;
    }

    this.intervalTimer = window.setInterval(() => this.next(words), this.options.interval ?? 2000);
  }

  protected async deactivateListeners(): Promise<void> {
    window.clearInterval(this.intervalTimer);
    window.clearInterval(this.rollTimer);

    await super.deactivateListeners();
  }

  private next(words: string[]): void {
    this.cursor = (this.cursor + 1) % words.length;
    const word = words[this.cursor];

    // For whoever asked for less motion, the word changes, and that is all.
    if (window.matchMedia?.('(prefers-reduced-motion: reduce)').matches) {
      this.wordEl.textContent = word;

      return;
    }

    const until = Date.now() + (this.options.roll ?? 500);

    this.el.classList.add('word-switcher--rolling');
    window.clearInterval(this.rollTimer);
    this.rollTimer = window.setInterval(() => {
      if (Date.now() < until) {
        this.wordEl.textContent = this.scramble(word.length);

        return;
      }

      window.clearInterval(this.rollTimer);
      this.el.classList.remove('word-switcher--rolling');
      this.wordEl.textContent = word;
    }, WORD_SWITCHER_TICK);
  }

  // As many letters as the word coming, drawn at random.
  private scramble(length: number): string {
    return Array.from({ length }, () => WORD_SWITCHER_LETTERS[Math.floor(Math.random() * WORD_SWITCHER_LETTERS.length)]).join('');
  }
}
