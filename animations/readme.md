Breakdance provides a simple way to replay animations that were initially triggered by viewport events (Entrance Animation).

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

**New in 2.0**: Animations are now automatically retriggered in the following elements:
- Slider elements
- Advanced Accordion element
- Tabs elements
- Popup element
