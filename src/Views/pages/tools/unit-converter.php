<?php
/**
 * Unit Converter View
 */
?>
<div class="tool-container" style="max-width: 900px; margin-inline: auto; padding-top: 2rem;">
    <!-- Tool Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; background: linear-gradient(135deg, var(--color-primary), #10B981); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            Unit Converter
        </h1>
        <p class="text-muted">Convert between various measurement units including length, weight, temperature, area, and volume instantly.</p>
    </div>

    <!-- Category Selector Tabs -->
    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;" id="converter-tabs">
        <button class="btn btn-secondary active-tab" data-category="length" style="font-size: 0.9rem; padding: 0.5rem 1.25rem; border-radius: var(--radius-full); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-ruler-combined"></i> Length
        </button>
        <button class="btn btn-secondary" data-category="weight" style="font-size: 0.9rem; padding: 0.5rem 1.25rem; border-radius: var(--radius-full); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-weight-scale"></i> Weight
        </button>
        <button class="btn btn-secondary" data-category="temperature" style="font-size: 0.9rem; padding: 0.5rem 1.25rem; border-radius: var(--radius-full); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-temperature-half"></i> Temperature
        </button>
        <button class="btn btn-secondary" data-category="area" style="font-size: 0.9rem; padding: 0.5rem 1.25rem; border-radius: var(--radius-full); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-chart-area"></i> Area
        </button>
        <button class="btn btn-secondary" data-category="volume" style="font-size: 0.9rem; padding: 0.5rem 1.25rem; border-radius: var(--radius-full); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-whiskey-glass"></i> Volume
        </button>
    </div>

    <!-- Converter Form Board -->
    <div class="premium-card" style="padding: 2.5rem; margin-bottom: 2rem;">
        <style>
            .conversion-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
                align-items: center;
            }
            @media (min-width: 768px) {
                .conversion-grid {
                    grid-template-columns: 1fr auto 1fr;
                }
            }
            .swap-btn {
                width: 44px;
                height: 44px;
                border-radius: var(--radius-full);
                background: var(--color-background);
                border: 1px solid var(--color-border);
                color: var(--color-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                margin-inline: auto;
            }
            .swap-btn:hover {
                transform: scale(1.1) rotate(180deg);
                background: var(--color-primary);
                color: white;
                border-color: var(--color-primary);
            }
            .panel-input-group {
                background: rgba(156, 163, 175, 0.03);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-lg);
                padding: 1.5rem;
                transition: border-color 0.2s;
            }
            .panel-input-group:focus-within {
                border-color: var(--color-primary);
            }
            .panel-input {
                width: 100%;
                background: transparent;
                border: none;
                font-size: 2rem;
                font-weight: 700;
                color: var(--color-text-primary);
                outline: none;
                margin-bottom: 0.5rem;
            }
            .panel-select {
                width: 100%;
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                padding: 0.5rem;
                color: var(--color-text-primary);
                outline: none;
                font-weight: 600;
                cursor: pointer;
            }
            .results-grid-card {
                background: rgba(156, 163, 175, 0.02);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                padding: 0.75rem 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        </style>

        <div class="conversion-grid">
            <!-- Source Panel -->
            <div class="panel-input-group">
                <input type="number" id="from-val" class="panel-input" value="1" step="any">
                <select id="from-unit" class="panel-select"></select>
            </div>

            <!-- Swap Trigger -->
            <button id="swap-units-btn" class="swap-btn" title="Swap Units">
                <i class="fa-solid fa-right-left"></i>
            </button>

            <!-- Target Panel -->
            <div class="panel-input-group">
                <input type="number" id="to-val" class="panel-input" value="1" step="any">
                <select id="to-unit" class="panel-select"></select>
            </div>
        </div>
    </div>

    <!-- Quick Reference Panel (Converts to all other units instantly) -->
    <div class="premium-card" style="padding: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;"><i class="fa-solid fa-list-check" style="color: var(--color-primary); margin-right: 0.5rem;"></i> All Conversions Reference</h3>
        <div id="reference-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
            <!-- Generated dynamically in JS -->
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // DOM elements
    const tabs = document.querySelectorAll("#converter-tabs button");
    const fromVal = document.getElementById("from-val");
    const toVal = document.getElementById("to-val");
    const fromUnit = document.getElementById("from-unit");
    const toUnit = document.getElementById("to-unit");
    const swapBtn = document.getElementById("swap-units-btn");
    const referenceList = document.getElementById("reference-list");

    // Definitions
    const unitData = {
        length: {
            defaultFrom: "m",
            defaultTo: "ft",
            units: {
                mm: { name: "Millimeter (mm)", base: 0.001 },
                cm: { name: "Centimeter (cm)", base: 0.01 },
                m: { name: "Meter (m)", base: 1.0 },
                km: { name: "Kilometer (km)", base: 1000.0 },
                in: { name: "Inch (in)", base: 0.0254 },
                ft: { name: "Foot (ft)", base: 0.3048 },
                yd: { name: "Yard (yd)", base: 0.9144 },
                mi: { name: "Mile (mi)", base: 1609.344 }
            }
        },
        weight: {
            defaultFrom: "kg",
            defaultTo: "lb",
            units: {
                mg: { name: "Milligram (mg)", base: 0.000001 },
                g: { name: "Gram (g)", base: 0.001 },
                kg: { name: "Kilogram (kg)", base: 1.0 },
                oz: { name: "Ounce (oz)", base: 0.028349523125 },
                lb: { name: "Pound (lb)", base: 0.45359237 },
                st: { name: "Stone (st)", base: 6.35029318 }
            }
        },
        temperature: {
            defaultFrom: "C",
            defaultTo: "F",
            units: {
                C: { name: "Celsius (°C)" },
                F: { name: "Fahrenheit (°F)" },
                K: { name: "Kelvin (K)" }
            }
        },
        area: {
            defaultFrom: "m2",
            defaultTo: "ft2",
            units: {
                cm2: { name: "Sq. Centimeter (cm²)", base: 0.0001 },
                m2: { name: "Sq. Meter (m²)", base: 1.0 },
                km2: { name: "Sq. Kilometer (km²)", base: 1000000.0 },
                in2: { name: "Sq. Inch (in²)", base: 0.00064516 },
                ft2: { name: "Sq. Foot (ft²)", base: 0.09290304 },
                ac: { name: "Acre (ac)", base: 4046.8564224 },
                ha: { name: "Hectare (ha)", base: 10000.0 }
            }
        },
        volume: {
            defaultFrom: "l",
            defaultTo: "gal",
            units: {
                ml: { name: "Milliliter (ml)", base: 0.001 },
                l: { name: "Liter (l)", base: 1.0 },
                m3: { name: "Cubic Meter (m³)", base: 1000.0 },
                floz: { name: "Fluid Ounce (fl oz)", base: 0.0295735295625 },
                cup: { name: "Cup (cup)", base: 0.2365882365 },
                pt: { name: "Pint (pt)", base: 0.473176473 },
                qt: { name: "Quart (qt)", base: 0.946352946 },
                gal: { name: "Gallon (gal)", base: 3.785411784 }
            }
        }
    };

    let activeCategory = "length";

    // Build Selector Menus
    function populateDropdowns() {
        const cat = unitData[activeCategory];
        
        fromUnit.innerHTML = "";
        toUnit.innerHTML = "";
        
        for (const [key, details] of Object.entries(cat.units)) {
            const optFrom = document.createElement("option");
            optFrom.value = key;
            optFrom.textContent = details.name;
            fromUnit.appendChild(optFrom);

            const optTo = document.createElement("option");
            optTo.value = key;
            optTo.textContent = details.name;
            toUnit.appendChild(optTo);
        }

        fromUnit.value = cat.defaultFrom;
        toUnit.value = cat.defaultTo;
    }

    // Mathematical Converter Core
    function convert(value, from, to, category) {
        if (isNaN(value)) return "";
        if (from === to) return value;

        const cat = unitData[category];

        // Temperature has custom formulas due to non-zero base offsets
        if (category === "temperature") {
            let celsius = 0;
            if (from === "C") celsius = value;
            else if (from === "F") celsius = (value - 32) * 5/9;
            else if (from === "K") celsius = value - 273.15;

            if (to === "C") return celsius;
            else if (to === "F") return celsius * 9/5 + 32;
            else if (to === "K") return celsius + 273.15;
        }

        // Standard multiplicative conversions via base unit
        const valInBase = value * cat.units[from].base;
        return valInBase / cat.units[to].base;
    }

    // Dynamic Sync handlers
    function syncFromToTo() {
        const val = parseFloat(fromVal.value);
        const res = convert(val, fromUnit.value, toUnit.value, activeCategory);
        if (res === "") {
            toVal.value = "";
        } else {
            toVal.value = Number(res.toFixed(6));
        }
        updateReferenceGrid();
    }

    function syncToToFrom() {
        const val = parseFloat(toVal.value);
        const res = convert(val, toUnit.value, fromUnit.value, activeCategory);
        if (res === "") {
            fromVal.value = "";
        } else {
            fromVal.value = Number(res.toFixed(6));
        }
        updateReferenceGrid();
    }

    // Render Quick Reference conversions for all other units
    function updateReferenceGrid() {
        const val = parseFloat(fromVal.value);
        const unit = fromUnit.value;
        const cat = unitData[activeCategory];
        
        referenceList.innerHTML = "";

        if (isNaN(val)) {
            referenceList.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: var(--color-text-secondary);">Please enter a valid number</div>`;
            return;
        }

        for (const [key, details] of Object.entries(cat.units)) {
            const converted = convert(val, unit, key, activeCategory);
            const formattedVal = Number(parseFloat(converted).toFixed(6));
            
            const card = document.createElement("div");
            card.className = "results-grid-card";
            card.innerHTML = `
                <span style="font-weight: 500; font-size: 0.9rem; color: var(--color-text-secondary);">${details.name.split(" (")[0]}</span>
                <span style="font-family: monospace; font-weight: 700; font-size: 1.05rem; color: var(--color-text-primary); text-align: right; margin-left: 0.5rem; word-break: break-all;">${formattedVal}</span>
            `;
            referenceList.appendChild(card);
        }
    }

    // Tabs click actions
    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active-tab"));
            this.classList.add("active-tab");

            activeCategory = this.getAttribute("data-category");
            populateDropdowns();
            syncFromToTo();
        });
    });

    // Swapper action
    swapBtn.addEventListener("click", function () {
        const tempUnit = fromUnit.value;
        fromUnit.value = toUnit.value;
        toUnit.value = tempUnit;
        
        const tempVal = fromVal.value;
        fromVal.value = toVal.value;
        toVal.value = tempVal;

        syncFromToTo();
    });

    // Trigger listeners
    fromVal.addEventListener("input", syncFromToTo);
    toVal.addEventListener("input", syncToToFrom);
    fromUnit.addEventListener("change", syncFromToTo);
    toUnit.addEventListener("change", syncFromToTo);

    // Initial load
    populateDropdowns();
    syncFromToTo();
});
</script>
