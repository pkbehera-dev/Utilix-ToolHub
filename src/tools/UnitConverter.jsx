import React, { useState } from 'react';
import { ArrowRightLeft } from 'lucide-react';

const UNITS_MAP = {
  length: { meters: 1, kilometers: 1000, miles: 1609.34, feet: 0.3048, inches: 0.0254 },
  weight: { kilograms: 1, grams: 0.001, pounds: 0.453592, ounces: 0.0283495 },
  temp: { celsius: "C", fahrenheit: "F", kelvin: "K" }
};

export default function UnitConverter() {
  const [category, setCategory] = useState('length');
  const [val, setVal] = useState(1);
  const [fromUnit, setFromUnit] = useState('meters');
  const [toUnit, setToUnit] = useState('kilometers');

  const units = Object.keys(UNITS_MAP[category]);

  const handleCategoryChange = (e) => {
    const cat = e.target.value;
    setCategory(cat);
    const newUnits = Object.keys(UNITS_MAP[cat]);
    setFromUnit(newUnits[0]);
    setToUnit(newUnits[1] || newUnits[0]);
  };

  const compute = () => {
    if (category === 'temp') {
      let cel = val;
      if (fromUnit === 'fahrenheit') cel = (val - 32) * 5/9;
      if (fromUnit === 'kelvin') cel = val - 273.15;

      let res = cel;
      if (toUnit === 'fahrenheit') res = (cel * 9/5) + 32;
      if (toUnit === 'kelvin') res = cel + 273.15;
      return res.toFixed(2);
    } else {
      const base = val * UNITS_MAP[category][fromUnit];
      const res = base / UNITS_MAP[category][toUnit];
      return res.toFixed(4);
    }
  };

  const result = compute();

  return (
    <div className="glass-card p-6">
      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Category</label>
        <select value={category} onChange={handleCategoryChange} className="form-select">
          <option value="length">Length (meters, km, miles, feet, inches)</option>
          <option value="weight">Weight (kg, grams, pounds, ounces)</option>
          <option value="temp">Temperature (°C, °F, Kelvin)</option>
        </select>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">From Value</label>
          <input
            type="number"
            value={val}
            onChange={(e) => setVal(parseFloat(e.target.value) || 0)}
            className="form-input mb-2"
          />
          <select value={fromUnit} onChange={(e) => setFromUnit(e.target.value)} className="form-select">
            {units.map(u => <option key={u} value={u}>{u}</option>)}
          </select>
        </div>

        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Converted Value</label>
          <input
            type="text"
            value={result}
            readOnly
            className="form-input readonly-input mb-2 text-blue-500 font-bold"
          />
          <select value={toUnit} onChange={(e) => setToUnit(e.target.value)} className="form-select">
            {units.map(u => <option key={u} value={u}>{u}</option>)}
          </select>
        </div>
      </div>
    </div>
  );
}
