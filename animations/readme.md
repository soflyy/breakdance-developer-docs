# Animations


Breakdance provides a simple way to replay entrance animations.

## Examples

Replay Animations on a Specific Element

```js
const panel = document.querySelector('.bde-accordion__panel');
const event = new Event("breakdance_play_animations", { bubbles: true });
panel.dispatchEvent(event);
```

Replay Animations on the Entire Website

```js
const event = new Event("breakdance_play_animations", { bubbles: true });
document.dispatchEvent(event);
```

Reset Entrance Animations

In addition to replaying animations, Breakdance also allows you to reset entrance animations to their initial hidden state. This is particularly useful when you want animations to play again when entering the viewport.

```js
const panel = document.querySelector('.bde-accordion__panel');
const event = new Event("breakdance_reset_animations", { bubbles: true });
panel.dispatchEvent(event);
```

**New in 2.0**: Animations are now automatically retriggered in the following elements:
- Slider elements
- Advanced Accordion element
- Tabs elements
- Popup element

Animations within these elements will automatically retrigger under the appropriate conditions (e.g., when a new slide appears, an accordion panel opens, etc.).
