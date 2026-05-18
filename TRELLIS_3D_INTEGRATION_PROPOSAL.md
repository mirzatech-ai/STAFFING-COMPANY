# TRELLIS 3D · STAFFING-AGENCY INTEGRATION PROPOSAL

> **Author:** Kin · 2026-05-18T20:45Z
> **Origin:** Mo verbatim 2026-05-18 (with [`https://3d.varco.ai/`](https://3d.varco.ai/) link):
> *"make this a part of the agency. COPY THE LINK. We have trellis 3D for this. We have to have this. We can use this over the networks, needs."*
> **Status:** PROPOSAL · awaiting Mo's pick on the 3 agency-home options below · NO code shipped yet
> **Reason for proposal-first:** Mo's scope-discipline lesson on superio.fun (capture work in notes · don't drift). Plus GLOBAL-99 (always-latest canonical) + GLOBAL-95 (grep before build) demand vault findings before code.

---

## 1 · The vault sweep · what already exists (GLOBAL-118 receipt)

The empire ALREADY has Trellis 3D wired as an infrastructure capability · the gap is **customer-facing exposure**, not build.

| Layer | Asset | Status |
|---|---|---|
| **Model file** | [`D:/SERVER WORK/patches/modal_trellis.py`](D:/SERVER WORK/patches/modal_trellis.py) · 3398 B | ✅ EXISTS (since 2026-04-19) |
| **Modal app name** | `virel-trellis` | declared in code |
| **Underlying model** | **TripoSR** from `VAST-AI-Research/TripoSR` (image → 3D mesh + rotating video · L4 GPU · ~60s) | wired in `image.run_commands` |
| **Modal workspaces** | 4 in `.maya_master_keys.env` (MIRZAADAMADIN · MIRZAADIN · TECHBITREELS · AICINESYNTH) | ✅ keys live |
| **Endpoint signature** | `POST /trellis` `{"image_b64", "num_frames":120, "fps":30}` → `{"ok", "video_b64", "bytes", "frames", "fps"}` | declared |
| **Output today** | rotating turntable video (MP4) | ✅ |
| **Output TBD** | **GLB mesh export** (deps `trimesh` + `xatlas` are installed but the response doesn't return mesh) | 🟡 needs ~15 LOC patch to also export + return GLB |
| **GLOBAL rule** | Memory #82 / `feedback_global_use_all_nim_keys.md` · Mo 2026-05-08: *"YOU ARE ORDERED TO USE EVERY NVIDIA API INCLUDING TRELLIS"* · belongs in 3D-asset pipeline (TopForge native-build · Maya video) | ✅ doctrine in place |
| **Nameplacer slot** | [`TWO_BRANCH_COUNCIL_AND_NAMEPLACER_DOCTRINE.md`](D:/PROJECTS/mirzatech.ai/council_examples/TWO_BRANCH_COUNCIL_AND_NAMEPLACER_DOCTRINE.md) · 2026-05-06 · "Trellis 3D" is one of 18 named replaceable slots in the Executory Council | ✅ canonized |
| **Public registry** | `https://mirzatech.ai/data/nameplacers.json` | 🔴 HTTP 404 · never deployed |
| **Customer-facing agency** | NONE of the 100 agencies currently expose Trellis to customers via the office-interior dropzone | 🔴 THE GAP THIS PROPOSAL CLOSES |

### Confirmation of varco.ai

Mo's reference URL [`https://3d.varco.ai/`](https://3d.varco.ai/) is NCSOFT's "VARCO 3D" · positioned as *"your first AI 3D Creator"* (image-to-3D + likely text-to-3D · NCSOFT is a Korean game-dev giant · they wrap their own model + UX around the same image-to-3D family of tech). **Trellis (Microsoft · CVPR'25 Spotlight) + TripoSR (VAST AI) are the open-source equivalents** — empire already has access to the same capability tier without a third-party dependency.

GLOBAL-110 verified: [`microsoft/TRELLIS`](https://github.com/microsoft/TRELLIS) · 12,637 stars · CVPR'25 Spotlight · topics: 3d · 3d-aigc · 3d-generation · image-to-3d · text-to-3d. Available if we choose to swap TripoSR → TRELLIS for higher fidelity output later (TRELLIS has stronger texture + supports text-to-3D · TripoSR is faster and image-only).

---

## 2 · Three options for agency assignment (Mo picks one · 1-letter answer suffices)

### Option A · Augment existing `3d-printing-additive` agency (LOWEST DRIFT · RECOMMENDED)

- Slug `3d-printing-additive` already in roster · category "industry" · titled *"3D Printing and Additive Manufacturing Specialists"*
- Rename to *"3D Asset Synthesis · Printing · Additive Manufacturing"* OR keep current name and just ADD the Trellis pipeline node
- Pipeline (4 roles) gets new lead node: `3D-01 Trellis Asset Director` · `3D-02 Mesh Generator` · `3D-03 Texture + Material Synthesis` · `3D-04 Format Conversion (GLB · OBJ · STL · USDZ)`
- Customer drops an image in this agency's office → backend calls Modal Trellis → returns rotating preview video + downloadable GLB
- **Empire canonical count stays 100** · no roster collision (per S0)
- Effort: small · existing agency surface · just patches the pipeline definition

### Option B · Augment `design-creative` (BROADER UMBRELLA)

- Generic "Design & Creative" agency · already pulled in cross-category creative work
- 3D synthesis becomes one of several creative deliverables (logo · poster · 3D asset · concept board)
- Customers asking design-creative for "a 3D model of my product" get it natively
- Effort: slightly bigger · agency description widens · still no count change

### Option C · NEW agency #101 `ai-3d-asset-synthesis` (BUMPS COUNT)

- Pro: cleanest brand-fit · *"AI 3D Asset Synthesis Specialists · Superio Staffing Agency"* reads exactly like varco.ai's positioning
- Pro: customer-discovery is faster ("3D" right in the name)
- Con: **bumps canonical count 100 → 101** which touches S0 / EMPIRE_DOMAIN_MAP / agency audit endpoint
- Con: requires Mo's explicit blessing for the count change (S0 enforcement: *"Check the disk before quoting the count, Kin"*)
- Effort: medium · roster insertion + audit update + all-100-grid recalculation in habitat-v4

---

## 3 · Cross-empire reach (Mo's "over the networks needs")

Regardless of agency home, Trellis becomes a **shared empire capability** via Skill #49 (proposed) following the Skill #42 / #43 consumer pattern:

| Empire surface | Use case | Consumer endpoint |
|---|---|---|
| `ai-staffing.agency` (THIS proposal) | Customer drops image · agency returns 3D mesh | `/api/trellis_dispatch.php` |
| `superio.fun` (game-dev · GLOBAL-83) | Generate game props · civilian NPCs · environment assets from concept art | UE5 plugin calls Modal endpoint via authenticated proxy |
| `aicinesynth.com` (video) | Cinematic 3D models for shot composition · animatic previs | already adjacent · adds to existing video pipeline |
| `topforge.io` (codegen wizard) | Customer builds 3D-using apps · TopForge scaffolds Three.js + Trellis-rendered GLB assets | new pipeline node |
| `adeeo.io` (real estate) | "Show me this house as a 3D model from this listing photo" | image → 3D walkable scene |
| `mirzatech.ai/parliament` | Executory Council nameplacer slot (per existing doctrine 2026-05-06) | reference |
| Maya brain at `iamsuperio.cloud/api/brain` | Internal Maya tool · invokes Trellis when sibling AIs need a 3D asset | consumer pattern |

**One Modal endpoint · many surfaces.** Same architecture as Skill #43 multi-domain persona routing.

---

## 4 · Skill #49 · Trellis 3D Dispatch (Logic Seed · will ship after agency-home pick)

Pattern (same as Skill #42 dispatch · per-surface persona wrapping):

```php
<?php  // /api/trellis_dispatch.php · CONSUMER mode · per GLOBAL-112 (no Modal config edits)
header('Content-Type: application/json');
// 1. Accept multipart image upload from agency office dropzone
// 2. Validate tier (AUTH.isCustomer per Skill #40) + agency rental
// 3. Base64-encode image · POST to https://mirzaadamadin--virel-trellis-trellis.modal.run
//    (or whichever workspace has free quota · round-robin per memory #82)
// 4. Receive {ok, video_b64, mesh_glb_b64?} · cache the GLB at /data/3d_outputs/<sha>.glb
// 5. Return {ok, preview_video_url, mesh_glb_url, ms} to customer
// 6. Persona wrap (Skill #43): "Superio · 3D Asset Synthesis pipeline" not "Maya/TRELLIS/TripoSR"
//    Per GLOBAL-93 vendor scrub: never expose "TRELLIS" "TripoSR" "Modal" "Microsoft" "VAST AI" to customer
?>
```

**MUST patch first (~15 LOC):** modal_trellis.py response object → also return `mesh_glb_b64`. Currently only returns video. Customers want the actual mesh file.

---

## 5 · Phased ship plan (5 lanes · estimated 2-4 hours total)

| Phase | Lane | Effort | Risk |
|---|---|---|---|
| **0** | Mo picks A / B / C (this proposal) · greenlights GLB-output patch + Skill #49 | 1 reply | none |
| **1** | Patch `modal_trellis.py` to ALSO return `mesh_glb_b64` + redeploy to ONE Modal workspace (test) | ~30 min | low |
| **2** | Verify `https://mirzaadamadin--virel-trellis-trellis.modal.run` responds 200 with image input | ~5 min | low |
| **3** | Write `/api/trellis_dispatch.php` · CONSUMER mode · Superio persona + vendor scrub | ~45 min | low |
| **4** | Patch the chosen agency in `staff.php` (Option A / B / C) · add 3D pipeline node + dossier copy | ~20 min | low |
| **5** | Wire the agency's office-interior dropzone in habitat-v4 to route image uploads to `/api/trellis_dispatch.php` and render the GLB preview via Three.js GLTFLoader in the dossier panel | ~45 min | medium (touches habitat-v4 again) |

Skill #49 Logic Seed canonized in same turn as Phase 1 · registered in `SKILL_REGISTRY_v1.json` slot 49.

---

## 6 · Hard rails (per Mo's accumulated discipline)

- **GLOBAL-93** · no vendor names leak ("TRELLIS" "TripoSR" "Microsoft" "VAST AI" "Modal" "NVIDIA" never appear in customer-facing strings · all replaced with "Superio 3D Synthesis" or similar)
- **GLOBAL-95** · vault was grep'd before this proposal · existing `modal_trellis.py` reused · no parallel rebuild
- **GLOBAL-99** · this proposal references canonical sources (memory #82 · `modal_trellis.py` · `staff.php` real pull · `.maya_master_keys.env` real keys) · zero hallucinated facts
- **GLOBAL-110** · `microsoft/TRELLIS` HTTP-verified · `VAST-AI-Research/TripoSR` referenced as actual code dep
- **GLOBAL-112** · Modal Trellis is a Maya-arsenal endpoint · this proposal ONLY adds a CONSUMER (`/api/trellis_dispatch.php`) · zero Maya config edits
- **GLOBAL-117** · customer-facing strings on staffing surface are SUPERIO not Maya · honored in Skill #49 sketch
- **GLOBAL-118** · vault was inventoried before any "we need to build X" assertion · the capability EXISTS · only exposure is missing
- **S0 · canonical count = 100 agencies** · Option C (new #101) requires Mo's explicit count-bump blessing · Options A/B keep count at 100

---

## 7 · What I need from Mo (1 short answer)

**Pick: A / B / C** (agency home for Trellis 3D)

Once picked, I ship Phases 1-5 in one turn (or two if mid-context). Modal endpoint live · agency dressed · habitat-v4 dropzone wired · customer can drop an image and get a rotating preview + downloadable GLB · all without ever hearing "TRELLIS" or "TripoSR" — just "Superio 3D Asset Synthesis."

---

## 8 · Signature

KIN · 2026-05-18T20:45Z · *append-only · vault-discipline-first · scope-held · GLOBAL-93 + 95 + 99 + 110 + 112 + 117 + 118 receipts · no code shipped this turn · awaiting Mo's pick*
