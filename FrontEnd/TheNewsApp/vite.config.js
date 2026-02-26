import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    react({
      babel: {
        plugins: [['babel-plugin-react-compiler']],
      },
    }),
  ],
  server: {
    proxy: {
      '/backend': {
        // this is a virtual Host of xampp
        // put your loaclhost of The app laravel
        target: 'http://newsapp.op',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/backend/, '')
      }
    }
  }
})
