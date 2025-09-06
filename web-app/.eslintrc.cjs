module.exports = {
  root: true,
  env: { browser: true, es2022: true, node: true },
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: '@typescript-eslint/parser',
    ecmaVersion: 'latest',
    sourceType: 'module',
    ecmaFeatures: { jsx: true }
  },
  plugins: ['vue', '@typescript-eslint', 'import'],
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:import/recommended',
    'plugin:import/typescript',
    'prettier'
  ],
  rules: {
    'vue/multi-word-component-names': 'off',
    'vue/jsx-uses-vars': 'error',
    'import/order': ['warn', { 'newlines-between': 'always', alphabetize: { order: 'asc', caseInsensitive: true } }],
    '@typescript-eslint/consistent-type-imports': 'warn'
  },
  ignorePatterns: ['dist', 'node_modules']
}
