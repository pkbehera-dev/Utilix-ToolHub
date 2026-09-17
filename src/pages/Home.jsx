import React, { useState } from 'react';
import { TOOLS, CATEGORIES } from '../data/toolsData';
import { Search, ChevronRight, Layers, ArrowUpRight } from 'lucide-react';

export default function Home({ onOpenSearch }) {
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');

  const filteredTools = TOOLS.filter(tool => {
    const matchesCategory = selectedCategory === 'all' || tool.category === selectedCategory;
    const matchesSearch =
      tool.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      tool.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
      tool.tags.some(t => t.toLowerCase().includes(searchTerm.toLowerCase()));
    return matchesCategory && matchesSearch;
  });

  return (
    <div className="space-y-12 py-6 animate-fade-in">
      {/* Hero Section */}
      <section className="text-center space-y-4 py-8">
        <h1 className="text-4xl sm:text-5xl font-black text-[var(--color-text-primary)] tracking-tight">
          Your Digital Utility Belt, <span className="text-blue-500">Refined.</span>
        </h1>
        <p className="text-base sm:text-lg text-[var(--color-text-secondary)] max-w-2xl mx-auto">
          Fast, free, and secure online tools for developers, designers, writers, and power users. Zero tracking, zero server latency.
        </p>

        {/* Hero Quick Search */}
        <div className="max-w-xl mx-auto pt-2">
          <div className="relative">
            <Search className="absolute left-4 top-3.5 w-5 h-5 text-[var(--color-text-secondary)]" />
            <input
              type="text"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              placeholder="Search 24+ free client-side tools..."
              className="w-full pl-12 pr-4 py-3 rounded-xl bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] placeholder-[var(--color-text-secondary)] outline-none focus:border-blue-500 shadow-lg text-sm transition-all"
            />
          </div>
        </div>
      </section>

      {/* Category Tabs */}
      <section className="space-y-6">
        <div className="flex items-center justify-between border-b border-[var(--color-border)] pb-4 flex-wrap gap-4">
          <div className="flex items-center gap-2 overflow-x-auto pb-1">
            <button
              onClick={() => setSelectedCategory('all')}
              className={`px-4 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer ${
                selectedCategory === 'all'
                  ? 'bg-blue-500 text-white shadow-md'
                  : 'bg-[var(--color-surface)] text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)] border border-[var(--color-border)]'
              }`}
            >
              All Utilities ({TOOLS.length})
            </button>
            {CATEGORIES.map(cat => (
              <button
                key={cat.slug}
                onClick={() => setSelectedCategory(cat.slug)}
                className={`px-4 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer ${
                  selectedCategory === cat.slug
                    ? 'bg-blue-500 text-white shadow-md'
                    : 'bg-[var(--color-surface)] text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)] border border-[var(--color-border)]'
                }`}
              >
                {cat.name}
              </button>
            ))}
          </div>

          <span className="text-xs text-[var(--color-text-secondary)]">
            Showing {filteredTools.length} tools
          </span>
        </div>

        {/* Tools Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {filteredTools.map(tool => (
            <a
              key={tool.slug}
              href={`#/tool/${tool.slug}`}
              className="glass-card p-5 flex flex-col justify-between group cursor-pointer"
            >
              <div>
                <div className="flex items-center justify-between mb-3">
                  <div className="p-2.5 rounded-lg bg-blue-500/10 text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <Layers className="w-5 h-5" />
                  </div>
                  <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-[var(--color-background)] border border-[var(--color-border)] text-[var(--color-text-secondary)]">
                    {tool.category}
                  </span>
                </div>

                <h3 className="font-bold text-base text-[var(--color-text-primary)] group-hover:text-blue-500 transition-colors mb-1">
                  {tool.name}
                </h3>
                <p className="text-xs text-[var(--color-text-secondary)] leading-relaxed line-clamp-2 mb-4">
                  {tool.description}
                </p>
              </div>

              <div className="flex items-center text-xs font-semibold text-blue-500 group-hover:translate-x-1 transition-transform">
                Open Tool <ArrowUpRight className="w-3.5 h-3.5 ml-1" />
              </div>
            </a>
          ))}
        </div>
      </section>
    </div>
  );
}
