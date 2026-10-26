import React, { useEffect, useMemo, useRef, useState } from "react";
import { Input } from "@/components/ui/input";

export interface SearchableSelectOption {
  value: string | number;
  label: string;
}

interface SearchableSelectProps {
  value: string | number | "" | null | undefined;
  options: SearchableSelectOption[];
  onChange: (value: string | number) => void;
  placeholder?: string;
  disabled?: boolean;
  minChars?: number;
  maxResults?: number;
}

/**
 * Combobox con filtrado local: no renderiza ninguna opción hasta que el
 * usuario ha escrito `minChars` caracteres. Pensado para listas grandes
 * (miles de jugadores) donde un <select> normal monta todas las opciones
 * de golpe y se vuelve lento.
 */
const SearchableSelect: React.FC<SearchableSelectProps> = ({
  value,
  options,
  onChange,
  placeholder = "Escribe para buscar...",
  disabled = false,
  minChars = 4,
  maxResults = 30,
}) => {
  const [query, setQuery] = useState("");
  const [isOpen, setIsOpen] = useState(false);
  const [highlighted, setHighlighted] = useState(0);
  const containerRef = useRef<HTMLDivElement>(null);

  const selectedOption = useMemo(
    () => options.find((opt) => String(opt.value) === String(value)),
    [options, value]
  );

  useEffect(() => {
    if (!isOpen) {
      setQuery(selectedOption?.label ?? "");
    }
  }, [selectedOption, isOpen]);

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setIsOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const matches = useMemo(() => {
    const trimmed = query.trim().toLowerCase();
    if (trimmed.length < minChars) return [];
    const filtered = options.filter((opt) => opt.label.toLowerCase().includes(trimmed));
    return filtered.slice(0, maxResults);
  }, [query, options, minChars, maxResults]);

  const selectOption = (opt: SearchableSelectOption) => {
    onChange(opt.value);
    setQuery(opt.label);
    setIsOpen(false);
  };

  const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (!isOpen || matches.length === 0) return;
    if (e.key === "ArrowDown") {
      e.preventDefault();
      setHighlighted((i) => Math.min(i + 1, matches.length - 1));
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setHighlighted((i) => Math.max(i - 1, 0));
    } else if (e.key === "Enter") {
      e.preventDefault();
      selectOption(matches[highlighted]);
    } else if (e.key === "Escape") {
      setIsOpen(false);
    }
  };

  const remaining = Math.max(0, minChars - query.trim().length);

  return (
    <div ref={containerRef} className="relative">
      <Input
        value={query}
        placeholder={placeholder}
        disabled={disabled}
        onFocus={(e) => {
          setIsOpen(true);
          setHighlighted(0);
          e.target.select();
        }}
        onChange={(e) => {
          setQuery(e.target.value);
          setIsOpen(true);
          setHighlighted(0);
        }}
        onKeyDown={handleKeyDown}
      />
      {isOpen && query.trim().length > 0 && (
        <div className="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-md border border-input bg-popover shadow-md">
          {remaining > 0 ? (
            <p className="px-3 py-2 text-sm text-muted-foreground">
              Escribe {remaining} caracter{remaining === 1 ? "" : "es"} más...
            </p>
          ) : matches.length === 0 ? (
            <p className="px-3 py-2 text-sm text-muted-foreground">Sin coincidencias</p>
          ) : (
            matches.map((opt, i) => (
              <button
                type="button"
                key={opt.value}
                onMouseDown={(e) => e.preventDefault()}
                onClick={() => selectOption(opt)}
                className={`block w-full truncate px-3 py-2 text-left text-sm ${
                  i === highlighted ? "bg-accent text-accent-foreground" : "hover:bg-accent/50"
                }`}
              >
                {opt.label}
              </button>
            ))
          )}
        </div>
      )}
    </div>
  );
};

export default SearchableSelect;
