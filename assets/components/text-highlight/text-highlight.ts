import Component from '@wexample/symfony-loader/js/Class/Component';

// Between two highlights coming into view together, so that they are drawn
// one after the other rather than at once.
const TEXT_HIGHLIGHT_STAGGER = 200;

let queuedUntil = 0;

export default class extends Component {
  private observer?: IntersectionObserver;

  private timer?: number;

  protected async activateListeners(): Promise<void> {
    await super.activateListeners();

    if (!('IntersectionObserver' in window)) {
      this.draw();

      return;
    }

    this.observer = new IntersectionObserver((entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        this.observer?.disconnect();
        this.observer = undefined;
        this.queue();
      }
    });
    this.observer.observe(this.el);
  }

  protected async deactivateListeners(): Promise<void> {
    this.observer?.disconnect();
    window.clearTimeout(this.timer);

    await super.deactivateListeners();
  }

  // Its turn after those already waiting.
  private queue(): void {
    const now = Date.now();
    const at = Math.max(now, queuedUntil);

    queuedUntil = at + TEXT_HIGHLIGHT_STAGGER;
    this.timer = window.setTimeout(() => this.draw(), at - now);
  }

  private draw(): void {
    this.el.classList.add('text-highlight--drawn');
  }
}
