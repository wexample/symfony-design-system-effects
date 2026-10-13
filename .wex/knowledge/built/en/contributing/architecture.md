## Architecture

`src/Avatar/Wexies2020AvatarEngine.php` implements the design system's `AvatarEngineInterface`; autoconfigured from `src/Resources/config/services.yaml`, it is tagged as an engine and reaches `AvatarService` with no wiring of its own.

The eyes are `src/Resources/avatar/wexies-2020/{a…z}.svg`, the owner's 2019 drawings taken from network's `front/partials/eyes/`, each brought to one square, `0 0 212.598 212.598`, the offset that placed it kept as a `translate`. The engine reads the inside of each file once, picks a pair with `AvatarHelper::avatarPick(seed, 'eyes', 26)` and a colour with `AvatarHelper::avatarColor(seed, 60, 50)`, and draws the disc, the sheen gradient of the original (`74% 100%` → `26% 0%`, white from 0 to 0.4) and the eyes in that square.

`src/Avatar/FacetsAvatarEngine.php` draws in a 100 square: three squares three times as large, each turned around the centre so that its top edge is a plane's edge. The plane facing the white corner stands further out than the two beside it, so no edge runs through the centre.

Tests: `tests/Unit/Avatar/Wexies2020AvatarEngineTest.php`, `FacetsAvatarEngineTest.php` — the 26 pairs, the same face for the same seed whatever the name, seeds spread over the faces.
