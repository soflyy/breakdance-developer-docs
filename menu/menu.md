After the page is loaded it is possible to access the Breakdance Menu's instance on its HTMLElement:

## Examples

```js
const menu = document.querySelector('.breakdance-menu').bdMenu;
```

```js
const dropdown = document.querySelector('.bde-menu-dropdown-72-105 .breakdance-dropdown');

menu.openDropdown(dropdown);

menu.closeDropdown(dropdown);

menu.closeAll();

menu.toggleMobileMenu();
```

## API

### openDropdown(...)

```typescript
openDropdown(node: HTMLElement)
```

Open a dropdown menu. It takes in an HTMLElement and then closes all other dropdowns except for the selected one. Works on mobile too.

| Param         | Type                                                              |
| ------------- | ----------------------------------------------------------------- |
| **`node`** | HTMLElement |


### closeDropdown(...)

```typescript
closeDropdown(node: HTMLElement)
```

Close a dropdown menu. It takes in an HTMLElement. Works on mobile too.

| Param         | Type                                                              |
| ------------- | ----------------------------------------------------------------- |
| **`node`** | HTMLElement |

### closeAll()

```typescript
closeAll()
```

Close all dropdown menus.

### getOpenDropdown()

```typescript
getOpenDropdown() => DropdownElement | null
```

Return the currently open dropdown element or null if none is open.

### refreshDropdowns()

```typescript
refreshDropdowns()
```

Refresh all dropdown menus. It recalculates the open dropdown width and aligns each dropdown element with the menu's container.


### toggleMobileMenu()

```typescript
toggleMobileMenu() => Promise<void>
```

Open or close the mobile menu. Returns as promise that is fullfilled once the animation has ended.


### isDesktop()

```typescript
isDesktop() => boolean
```

Determine wheter the menu is showing the desktop version.

### isMobile()

```typescript
isMobile() => boolean
```

Determine wheter the menu is showing the mobile version.

### isVertical()

```typescript
isVertical() => boolean
```

Determine whether the menu is set to be displayed vertically.
