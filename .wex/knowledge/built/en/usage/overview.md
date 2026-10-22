## Avatars: `wexies-2020`

A disc of the seed's colour under a white sheen, and one of 26 pairs of white eyes drawn by hand — happy arcs, crosses, hearts, a cyclops, a monocle, sleepy lids —, each set somewhere in the face so that it seems to look somewhere. Drawn through the design system's `avatar()` (see its `usage/avatars` page):

```twig
{{ avatar(user.id, { engine: 'wexies-2020' }) }}
```

To make it every avatar's default, set `wexample_symfony_design_system.avatar.default_engine: wexies-2020` in the application's parameters.

The face follows the seed and nothing else. The original read the name — a rename changed the face — and, from 2021 on, a random generator that changed the colour at every request: neither came back.

## Avatars: `facets`

Three planes of colour laid across a white square, each cut by a straight edge, darker where they cross (`mix-blend-mode: multiply`), and a corner left white. The colours are neighbours on the wheel from a hue of the seed; the corner, the angles and how far each edge stands from the centre are the seed's too.

```twig
{{ avatar(user.id, { engine: 'facets' }) }}
```

## Text effects

For a page that speaks loud — a landing, a hero:

```twig
Des solutions {{ text_highlight('sur-mesure') }} et {{ text_highlight('durables', { tone: 'grass' }) }}.
Le réseau de freelances {{ word_switcher(['inclusif', 'solidaire', 'généreux']) }}.
```

- **`text_highlight(text, { tone, class })`**: a pill of colour behind the lower part of the words, slightly tilted, drawn from the middle outwards once they come into view; several on a page are drawn one after the other. `tone` is a name of the categorical palette (`aqua` by default).
- **`word_switcher(words, { interval, roll, class })`**: every `interval` ms (2000), the word rolls through random letters for `roll` ms (500), as many as the coming word, then settles on it. Screen readers read the list, once.

Under `prefers-reduced-motion`, the pill is there from the start and the word changes without rolling.
