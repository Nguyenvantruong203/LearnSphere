# Vue 3 TSX Frontend Project

## TL;DR for AI

**Framework**: Vue 3 (Composition API) + TSX (JSX-style)  
**UI**: Ant Design Vue (forms/controls) + Tailwind (layout/spacing)  
**Router**: vue-router (history mode)  
**Auth/2FA**: Laravel Fortify + Sanctum (cookie-based)  
**Node**: ≥ 20.19 (recommended 22 LTS)

**Important Rules**:

- No switching to SFC/React
- No additional UI libraries
- All requests go through `src/api/*`
- Small PRs (< 400 LOC changes)
- Conventional Commits with screenshots for UI changes
- TSX input pattern: `value` + `onUpdate:value` (instead of `v-model`)
- Theme AntD via `ConfigProvider.theme.token` - no deep CSS overrides

## Table of Contents

- [Purpose & Scope](#purpose--scope)
- [Architecture & Stack](#architecture--stack)
- [Project Structure](#project-structure)
- [Naming Conventions](#naming-conventions)
- [Ant Design Vue & Tailwind Usage](#ant-design-vue--tailwind-usage)
- [TSX in Vue - Standard Patterns](#tsx-in-vue---standard-patterns)
- [API Layer - Principles & Examples](#api-layer---principles--examples)
- [Routing & Guards](#routing--guards)
- [2FA Flow (Fortify + Sanctum)](#2fa-flow-fortify--sanctum)
- [ESLint & Prettier](#eslint--prettier)
- [Git, Branches & PR Guidelines](#git-branches--pr-guidelines)
- [Accessibility & i18n](#accessibility--i18n)
- [Performance & Optimization](#performance--optimization)
- [Security & Logging](#security--logging)
- [Theming & Dark Mode](#theming--dark-mode)
- [Component Templates](#component-templates)
- [Adding New Pages/Routes](#adding-new-pagesroutes)
- [Environment & Build](#environment--build)
- [What NOT to Do](#what-not-to-do)

## Purpose & Scope

This document defines binding rules for all AI-generated changes (source code, configuration, documentation).

Applies to the entire frontend SPA and related parts: build, routing, theme, API layer, auth guards.

Do not adjust large architecture without explicit user requirements.

## Architecture & Stack

- **Build**: Vite + Vue 3 (Composition API) + TypeScript (strict) + TSX (JSX)
- **UI Components**: Ant Design Vue (controls, Form, Modal, Table...)
- **Styling**: TailwindCSS (layout/spacing/utilities: flex, grid, gap, p-/m-/rounded-...)
- **Routing**: vue-router (navigation, guards)
- **State**: (Optional) Pinia store for shared state when needed
- **Auth/2FA**: Integration with Laravel Fortify + Sanctum (cookie + CSRF)
- **Environment**: `VITE_API_BASE_URL` in `.env`
- **Node**: ≥ 20.19 (prefer 22 LTS). If EBADENGINE error → upgrade Node

## Project Structure

**Standard folder structure (must maintain):**

```
src/
  api/            # HTTP layer (fetch wrapper + domain APIs)
  components/     # Small, reusable UI components
  pages/          # Route-level pages (TSX)
  router/         # vue-router config & guards
  stores/         # (optional) Pinia stores
  styles/         # (if additional CSS needed)
  utils/          # Pure TS helpers
  index.css       # Tailwind entry point
```

## Naming Conventions

- **Components/Pages**: PascalCase, e.g., `AdminLogin.tsx`, `CustomerLogin.tsx`
- **Helpers/Utils/APIs**: camelCase file names, e.g., `authApi.ts`, `http.ts`
- **Route paths**: kebab-case (`/admin/dashboard`)
- **Imports**: Use `@/` alias (configured in `vite.config.ts`)
- **No deep relative imports** like `../../../`

## Ant Design Vue & Tailwind Usage

### Ant Design Vue for:

- **Forms** (required), Input, Button, Select, Modal, Table, Alert, Message/Notification
- **Validation** in `Form.Item.rules`
- **Async feedback**: `message.success/error`

### Tailwind for:

- **Layout/spacing/responsive**: `flex`, `grid`, `gap-*`, `p-*`, `m-*`, `w-*`, `rounded-*`, `shadow-*`, `min-h-screen`, etc.

### Theme Configuration:

```typescript
// main.ts
h(ConfigProvider, {
  theme: { token: { colorPrimary: '#14b8a6', borderRadius: 12 } }
}, ...)
```

### ❌ Don't:

- Add conflicting UI kits (MUI, Element, Naive...)
- Convert TSX to SFC if file is already TSX
- Override AntD theme with manual CSS

## TSX in Vue - Standard Patterns

### Controlled Input (Required):

```tsx
<Input
  value={state.email}
  onUpdate:value={(v) => (state.email = v as string)}
/>
```

### Events:

- Use camelCase: `onClick`, `onChange`, `onFinish`
- Form submit: `<Form onFinish={handleSubmit}>...</Form>`

### Conditional Rendering:

```tsx
{
  err.value && <Alert type="error" message={err.value} show-icon />;
}
```

### Separate Logic from JSX:

- Process in `setup()` or helpers in `utils/`
- JSX only for UI description

## API Layer - Principles & Examples

**Required**: All HTTP requests go through `src/api/`. No fetch in components (except tiny demos).

### `src/api/http.ts` (fetch wrapper):

```typescript
const API_BASE = import.meta.env.VITE_API_BASE_URL;

type HttpOptions = {
  method?: "GET" | "POST" | "PUT" | "PATCH" | "DELETE";
  headers?: Record<string, string>;
  body?: unknown;
  withCredentials?: boolean; // for Sanctum
};

export async function http(path: string, opts: HttpOptions = {}) {
  const res = await fetch(`${API_BASE}${path}`, {
    method: opts.method ?? "GET",
    credentials: opts.withCredentials ? "include" : "same-origin",
    headers: {
      "Content-Type": "application/json",
      ...(opts.headers ?? {}),
    },
    body: opts.body ? JSON.stringify(opts.body) : undefined,
  });

  const isJson = res.headers.get("content-type")?.includes("application/json");
  const data = isJson ? await res.json() : await res.text();

  if (!res.ok) {
    const msg = isJson && data?.message ? data.message : `HTTP ${res.status}`;
    throw new Error(msg);
  }

  return data;
}
```

### `src/api/authApi.ts`:

```typescript
import { http } from "./http";

export type LoginPayload = {
  email: string;
  password: string;
  target: "user" | "admin";
  totp?: string;
  rememberDevice?: boolean;
};

export const authApi = {
  // Call before login if using Sanctum cookie (CSRF)
  async initCsrf() {
    return await http("/sanctum/csrf-cookie", { withCredentials: true });
  },

  async login(payload: LoginPayload) {
    return await http("/api/login", {
      method: "POST",
      body: payload,
      withCredentials: true,
    });
  },

  async verify2fa(challengeId: string, totp: string, rememberDevice?: boolean) {
    return await http("/api/2fa/verify", {
      method: "POST",
      body: { challengeId, totp, rememberDevice },
      withCredentials: true,
    });
  },

  async logout() {
    return await http("/api/logout", { method: "POST", withCredentials: true });
  },
};
```

## Routing & Guards

`src/router/index.ts` is the only place for route/guard configuration.

Pages requiring permissions: set `meta.requiresAuth = true` and `meta.roles = [...]`.

Guards don't call APIs synchronously; only rely on existing state/cookies.

```typescript
router.beforeEach((to, _from, next) => {
  const raw = localStorage.getItem("auth_user");
  const user = raw ? JSON.parse(raw) : null;

  if (to.meta.requiresAuth && !user) return next("/admin/login");

  if (to.meta.roles && user && !to.meta.roles.includes(user.role)) {
    return next("/403");
  }

  next();
});
```

## 2FA Flow (Fortify + Sanctum)

1. `await authApi.initCsrf()` (if using cookie-based)
2. `const res = await authApi.login(payload)`
3. If returns `{ requires2fa: true, challengeId }` → show TOTP input
4. `await authApi.verify2fa(challengeId, totp, rememberDevice)`
5. Save user (and token if backend returns) → navigate
6. `logout()` clears state, navigates to `/login`

**UI Constraints**:

- TOTP input: only 6 digits, strip non-digits
- Display server errors (`message`)

## ESLint & Prettier

Run `npm run lint` before opening PR (or `lint:fix`).

Key rules:

- `vue/jsx-uses-vars: error`
- `@typescript-eslint/consistent-type-imports: warn`
- `import/order: warn` (alphabetize)

Don't disable rules with `eslint-disable` unless clear reason (must comment).

## Git, Branches & PR Guidelines

### Branch Naming:

- `feat/<scope>-<short-desc>`
- `fix/<scope>-<short-desc>`
- `chore/<scope>-<short-desc>`
- `refactor/<scope>-<short-desc>`

### Conventional Commits:

- `feat(auth): add TOTP verify step`
- `fix(login): prevent double submit`
- `chore(deps): bump ant-design-vue to 4.x`
- `docs: add guidelines for 2FA flow`

### PR Checklist (AI must self-check):

- [ ] Lint/format pass
- [ ] No more than ~400 lines of pure code changes per PR
- [ ] Clear description with screenshots (if UI)
- [ ] No unnecessary packages
- [ ] No scattered fetch; uses `api/`
- [ ] Doesn't touch files outside scope
- [ ] Updated README.md if convention/flow changes

## Accessibility & i18n

- Icon-only buttons must have `aria-label`
- Images must have `alt`
- UI content in Vietnamese by default (or per screen design)
- If adding multi-language later, use i18n plugin - don't create custom solution

## Performance & Optimization

- Dynamic imports for rarely used pages:
  ```typescript
  { path: '/admin/dashboard', component: () => import('@/pages/admin/Dashboard.vue') }
  ```
- Avoid unnecessary re-renders: extract child components
- Debounce for heavy search/typing

## Security & Logging

- Don't log passwords/totp/tokens
- Use `withCredentials` for Sanctum cookie-based calls
- CSRF: call `initCsrf()` before login when needed
- Hide internal system error messages; show user-friendly messages

## Theming & Dark Mode

- Admin login currently applies dark theme locally via ConfigProvider wrapper
- For global dark theme: use `theme.darkAlgorithm` in `main.ts` (consider impact)
- All color customization prefer `token` over CSS overrides

## Component Templates

### Copy-paste Vue template:

```vue
<template>
  <div class="p-6 max-w-xl mx-auto">
    <Form
      layout="vertical"
      :model="form"
      :rules="rules"
      @finish="handleSubmit"
    >
      <Form.Item name="title" label="Title">
        <Input v-model:value="form.title" :disabled="loading" />
      </Form.Item>

      <Button
        type="primary"
        html-type="submit"
        :loading="loading"
        :disabled="!form.title?.trim()"
      >
        Submit
      </Button>
    </Form>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { Form, Input, Button, message } from 'ant-design-vue'

const form = reactive({
  title: ''
})

const rules = {
  title: [
    { required: true, message: 'Vui lòng nhập tiêu đề' },
    { validator: (_: any, v: string) => (v?.trim()?.length ? Promise.resolve() : Promise.reject('Tiêu đề không được để trống')) }
  ]
}

const loading = ref(false)

const handleSubmit = async () => {
  loading.value = true
  try {
    // await featureApi.create({ title: form.title.trim() })
    message.success('Created!')
    form.title = ''
  } catch (e: any) {
    message.error(e?.message ?? 'Action failed')
  } finally {
    loading.value = false
  }
}
</script>
```

## Adding New Pages/Routes

### AI Checklist:

1. Create file in `src/pages/FeatureName.tsx` (PascalCase)
2. Create/adjust API in `src/api/featureApi.ts` (no direct fetch)
3. Add route in `src/router/index.ts`
4. Use AntD for forms; validate with `rules`
5. Layout with Tailwind
6. Manual test (UI screenshots), run lint + format
7. Create small PR with clear description

## Environment & Build

### `.env.example`:

```ini
VITE_API_BASE_URL=http://localhost:8000
```

Use `import.meta.env.VITE_API_BASE_URL`.

### Scripts:

`dev`, `build`, `preview`, `lint`, `lint:fix`, `format`

## What NOT to Do

❌ **Don't switch** stack to React/Next or SFC if file is already TSX  
❌ **Don't add** other UI libs besides AntD + Tailwind  
❌ **Don't fetch** directly in large components; bypass `api/` layer  
❌ **Don't override** AntD stylesheet deeply with manual CSS  
❌ **Don't commit** build files (`dist/`) or "strange" lockfile changes when not touching deps

## Development Workflow

### Getting Started

```bash
# Install dependencies
npm install

# Start development server
npm run dev

# Run linting
npm run lint

# Build for production
npm run build
```

### Before Submitting PR

1. Run `npm run lint:fix`
2. Test functionality manually
3. Take screenshots for UI changes
4. Ensure < 400 LOC changes
5. Write clear commit messages
6. Update documentation if needed

## FAQ for AI

**Need SSR/SEO?** → No, this is SPA. For marketing websites, create separate Nuxt/Next project.

**Change theme?** → Only via `ConfigProvider.theme.token`

**EBADENGINE error?** → Upgrade Node ≥ 20.19 (prefer 22 LTS)

**2FA input?** → TOTP 6 digits, strip non-digits, light client-side validation before API call

## Changelog

**v1.0** — Initial standards for Vue 3 + TSX + AntD + Tailwind; defined API layer, routing guards, 2FA flow, PR rules
