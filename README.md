# symfony-design-system-effects

Version: 2.0.2

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

## Table of Contents

- [Avatars: `wexies-2020`](#avatars-wexies-2020)
- [Avatars: `facets`](#avatars-facets)
- [Text effects](#text-effects)
- [Architecture](#architecture)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

## Architecture

`src/Avatar/Wexies2020AvatarEngine.php` implements the design system's `AvatarEngineInterface`; autoconfigured from `src/Resources/config/services.yaml`, it is tagged as an engine and reaches `AvatarService` with no wiring of its own.

The eyes are `src/Resources/avatar/wexies-2020/{a…z}.svg`, the owner's 2019 drawings taken from network's `front/partials/eyes/`, each brought to one square, `0 0 212.598 212.598`, the offset that placed it kept as a `translate`. The engine reads the inside of each file once, picks a pair with `AvatarHelper::avatarPick(seed, 'eyes', 26)` and a colour with `AvatarHelper::avatarColor(seed, 60, 50)`, and draws the disc, the sheen gradient of the original (`74% 100%` → `26% 0%`, white from 0 to 0.4) and the eyes in that square.

`src/Avatar/FacetsAvatarEngine.php` draws in a 100 square: three squares three times as large, each turned around the centre so that its top edge is a plane's edge. The plane facing the white corner stands further out than the two beside it, so no edge runs through the centre.

Tests: `tests/Unit/Avatar/Wexies2020AvatarEngineTest.php`, `FacetsAvatarEngineTest.php` — the 26 pairs, the same face for the same seed whatever the name, seeds spread over the faces.

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

Visit the [Wexample Suite documentation](https://docs.wexample.com) for the complete package ecosystem.

## Dependencies

- php: >=8.5
- wexample/symfony-helpers: >=16.0.0
- wexample/symfony-design-system: >=35.0.0

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
