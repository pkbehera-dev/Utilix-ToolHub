import React, { useState, useEffect, useRef } from 'react';
import { Search, X, ChevronRight, Layers } from 'lucide-react';
import { TOOLS, CATEGORIES } from '../data/toolsData';

export default function CommandPalette({ isOpen, onClose, onSelectTool }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const inputRef = useRef(null);

  useEffect(() => {
    if (isOpen) {
      setTimeout(() => inputRef.current?.focus(), 50);
    } else {
      setSearchTerm('');
      setSelectedCategory('all');
    }
  }, [isOpen]);

  useEffect(() => {
    const handleKeyDown = (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        if (isOpen) onClose(); else onSelectTool(null); // toggle search modal
      }
      if (e.key === 'Escape' && isOpen) {
        onClose();
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [isOpen, onClose, onSelectTool]);

  if (!isOpen) return null;

  const filteredTools = TOOLS.filter(tool => {
    const matchesCategory = selectedCategory === 'all' || tool.category === selectedCategory;
    const matchesSearch =
      tool.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      tool.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
      tool.tags.some(t => t.toLowerCase().includes(searchTerm.toLowerCase()));
    return matchesCategory && matchesSearch;
  });

  return (
    <div
      className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-start justify-center pt-16 px-4 animate-fade-in"
      onClick={onClose}
    >
      <div
        className="w-full max-w-2xl bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl shadow-2xl overflow-hidden"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Search Input Bar */}
        <div className="flex items-center px-4 py-3 border-b border-[var(--color-border)] gap-3">
          <Search className="w-5 h-5 text-blue-500" />
          <input
            ref={inputRef}
            type="text"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder="Type to search tools or categories..."
            className="w-full bg-transparent text-[var(--color-text-primary)] placeholder-[var(--color-text-secondary)] outline-none text-base"
          />
          <button onClick={onClose} className="p-1 rounded text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)]">
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Category Pills */}
        <div className="flex items-center gap-1.5 px-4 py-2 border-b border-[var(--color-border)] overflow-x-auto bg-[var(--color-background)]/50">
          <button
            onClick={() => setSelectedCategory('all')}
            className={`px-3 py-1 text-xs rounded-full transition-colors cursor-pointer whitespace-nowrap ${
              selectedCategory === 'all'
                ? 'bg-blue-500 text-white font-medium'
                : 'bg-[var(--color-surface)] text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)]'
            }`}
          >
            All Tools ({TOOLS.length})
          </button>
          {CATEGORIES.map(cat => (
            <button
              key={cat.slug}
              onClick={() => setSelectedCategory(cat.slug)}
              className={`px-3 py-1 text-xs rounded-full transition-colors cursor-pointer whitespace-nowrap ${
                selectedCategory === cat.slug
                  ? 'bg-blue-500 text-white font-medium'
                  : 'bg-[var(--color-surface)] text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)]'
              }`}
            >
              {cat.name}
            </button>
          ))}
        </div>

        {/* Results List */}
        <div className="max-h-96 overflow-y-auto p-2 divide-y divide-[var(--color-border)]/50">
          {filteredTools.length > 0 ? (
            filteredTools.map(tool => (
              <a
                key={tool.slug}
                href={`#/tool/${tool.slug}`}
                onClick={() => { onClose(); }}
                className="flex items-center justify-between p-3 rounded-lg hover:bg-[var(--color-surface-hover)] transition-colors group cursor-pointer"
              >
                <div className="flex items-center gap-3">
                  <div className="p-2 rounded-lg bg-blue-500/10 text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <Layers className="w-4 h-4" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-sm text-[var(--color-text-primary)] group-hover:text-blue-500 transition-colors">
                      {tool.name}
                    </h4>
                    <p className="text-xs text-[var(--color-text-secondary)] line-clamp-1">{tool.description}</p>
                  </div>
                </div>
                <ChevronRight className="w-4 h-4 text-[var(--color-text-secondary)] opacity-0 group-hover:opacity-100 transition-opacity" />
              </a>
            ))
          ) : (
            <div className="p-8 text-center text-sm text-[var(--color-text-secondary)]">
              No tools found matching "{searchTerm}"
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
