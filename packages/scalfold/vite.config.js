import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import path from 'path';
export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  server: {
    cors: true
  },
  build: {
    outDir: path.join(__dirname, "html/kaizen-nexus/dist"),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
        input: {
            style: path.resolve(__dirname, 'html/kaizen-nexus/assets/css/tailwind.css'),
        }
    }
  }
})
