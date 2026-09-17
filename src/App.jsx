import React, { useState, useEffect } from 'react';
import Header from './components/Header';
import Footer from './components/Footer';
import CommandPalette from './components/CommandPalette';
import Home from './pages/Home';
import ToolView from './pages/ToolView';
import About from './pages/About';
import Privacy from './pages/Privacy';
import Terms from './pages/Terms';

export default function App() {
  const [currentHash, setCurrentHash] = useState(window.location.hash || '#/');
  const [theme, setTheme] = useState(() => localStorage.getItem('theme') || 'dark');
  const [isSearchOpen, setIsSearchOpen] = useState(false);

  useEffect(() => {
    const handleHashChange = () => {
      setCurrentHash(window.location.hash || '#/');
      window.scrollTo(0, 0);
    };
    window.addEventListener('hashchange', handleHashChange);
    return () => window.removeEventListener('hashchange', handleHashChange);
  }, []);

  useEffect(() => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  }, [theme]);

  const toggleTheme = () => {
    setTheme(prev => prev === 'dark' ? 'light' : 'dark');
  };

  // Route Dispatcher
  const renderRoute = () => {
    if (currentHash.startsWith('#/tool/')) {
      const slug = currentHash.replace('#/tool/', '');
      return <ToolView slug={slug} />;
    }
    if (currentHash === '#/about') return <About />;
    if (currentHash === '#/privacy') return <Privacy />;
    if (currentHash === '#/terms') return <Terms />;
    return <Home onOpenSearch={() => setIsSearchOpen(true)} />;
  };

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)] text-[var(--color-text-primary)] transition-colors">
      <Header
        onOpenSearch={() => setIsSearchOpen(true)}
        theme={theme}
        toggleTheme={toggleTheme}
      />

      <main className="flex-1 max-w-6xl w-full mx-auto px-4 py-6">
        {renderRoute()}
      </main>

      <Footer />

      <CommandPalette
        isOpen={isSearchOpen}
        onClose={() => setIsSearchOpen(false)}
        onSelectTool={(tool) => setIsSearchOpen(false)}
      />
    </div>
  );
}
