import React from "react";
import type { FieldConfig } from "./DynamicForm";
import { Input } from "@/components/ui/input";
import { Switch } from "@/components/ui/switch";
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import SearchableSelect from "./SearchableSelect";
import { Plus, Trash2 } from "lucide-react";

interface DynamicFieldArrayProps {
  field: FieldConfig;
  data: any[];
  setData: (name: string, value: any) => void;
  errors: Record<string, any>;
}

/** Valor reservado para la opción "Seleccionar" (vaciar el campo) en los selects de un dynamicArray. */
export const CLEAR_OPTION_VALUE = "__clear__";

const DynamicFieldArray: React.FC<DynamicFieldArrayProps> = ({ field, data, setData, errors }) => {
  const parseSelectValue = (config: FieldConfig, value: string) => {
    if (value === CLEAR_OPTION_VALUE) return null;
    const matchedOption = config.options?.find((opt) => String(opt.value) === value);
    if (matchedOption && typeof matchedOption.value === "number") {
      const parsed = Number(value);
      return Number.isNaN(parsed) ? value : parsed;
    }
    return value;
  };

  const addRow = () => {
    const newRow: Record<string, any> = {};
    field.fields?.forEach((f) => {
      if (f.type === "boolean") {
        newRow[f.name] = false;
      } else if (f.hasOwnProperty('default')) {
        // @ts-ignore
        newRow[f.name] = f.default;
      } else {
        newRow[f.name] = "";
      }
    });
    setData(field.name, [...data, newRow]);
  };

  const removeRow = (index: number) => {
    const newData = data.filter((_, i) => i !== index);
    setData(field.name, newData);
  };

  const handleChange = (index: number, name: string, value: any) => {
    const newData = [...data];
    newData[index][name] = value;
    setData(field.name, newData);
  };

  return (
    <div className="space-y-2">
      <label className="block font-medium">{field.label}</label>

      {data.map((row, index) => (
        <div key={index} className="flex flex-wrap gap-2 items-center border border-border/50 rounded-lg px-3 py-2 bg-muted/20">
          {field.fields?.map((f, fi) => (
            // skip rendering hidden fields (they still exist in row data)
            // la key lleva el índice porque dos campos pueden compartir `name` (dos selectores sobre la misma clave)
            f.hidden ? null : (
            <div key={`${f.name}_${fi}`} className={f.type === "boolean" ? "flex-none" : "flex-1 min-w-[120px]"}>
              {f.type === "boolean" ? (
                <label
                  className={`flex items-center gap-2 px-3 h-9 rounded-md border cursor-pointer transition-all select-none ${
                    row[f.name]
                      ? "border-primary/50 bg-primary/10 text-primary"
                      : "border-input bg-background text-muted-foreground hover:border-primary/30 hover:bg-primary/5"
                  }`}
                >
                  <span className="text-sm font-medium whitespace-nowrap">{f.label}</span>
                  <Switch
                    checked={!!row[f.name]}
                    onCheckedChange={(checked) => handleChange(index, f.name, checked)}
                    disabled={Boolean((f as any).disabled)}
                  />
                </label>
              ) : f.type === "searchSelect" ? (
                <SearchableSelect
                  value={row[f.name]}
                  options={f.options ?? []}
                  onChange={(value) => handleChange(index, f.name, parseSelectValue(f, String(value)))}
                  disabled={Boolean((f as any).disabled)}
                  placeholder={`Buscar ${f.label.toLowerCase()}...`}
                />
              ) : f.type === "select" ? (
                <>
                  <label className="block text-xs text-muted-foreground mb-1">{f.label}</label>
                  <Select
                    value={row[f.name] === null || row[f.name] === undefined || row[f.name] === "" ? "" : String(row[f.name])}
                    onValueChange={(value) => handleChange(index, f.name, parseSelectValue(f, value))}
                    disabled={Boolean((f as any).disabled)}
                  >
                    <SelectTrigger className="w-full">
                      <SelectValue placeholder="Seleccione..." />
                    </SelectTrigger>
                    <SelectContent>
                      {f.options?.map((opt) => (
                        <SelectItem key={String(opt.value)} value={String(opt.value)}>
                          {opt.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </>
              ) : f.type === "toggleButtons" ? (
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">{f.label}</label>
                  <div className="flex gap-1">
                    {f.options?.map((opt) => {
                      const current = row[f.name];
                      const isActive =
                        current !== null && current !== undefined && current !== "" && String(current) === String(opt.value);
                      return (
                        <button
                          key={String(opt.value)}
                          type="button"
                          disabled={Boolean((f as any).disabled)}
                          onClick={() => handleChange(index, f.name, isActive ? null : parseSelectValue(f, String(opt.value)))}
                          className={`px-3 h-9 rounded-md border text-sm font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed ${
                            isActive
                              ? "border-primary/50 bg-primary/10 text-primary"
                              : "border-input bg-background text-muted-foreground hover:border-primary/30 hover:bg-primary/5"
                          }`}
                        >
                          {opt.label}
                        </button>
                      );
                    })}
                  </div>
                </div>
              ) : (
                <>
                  <label className="block text-xs text-muted-foreground mb-1">{f.label}</label>
                  <Input
                    type={f.type}
                    value={row[f.name]}
                    onChange={(e) => handleChange(index, f.name, e.target.value)}
                    placeholder={f.label}
                    disabled={Boolean((f as any).disabled)}
                  />
                </>
              )}

              {errors?.[field.name]?.[index]?.[f.name] && (
                <p className="text-destructive text-sm mt-1">
                  {errors[field.name][index][f.name]}
                </p>
              )}
            </div>
            )
          ))}

          <Button
            type="button"
            variant="ghost"
            size="icon"
            className="text-muted-foreground hover:text-destructive hover:bg-destructive/10 shrink-0"
            onClick={() => removeRow(index)}
          >
            <Trash2 className="w-4 h-4" />
          </Button>
        </div>
      ))}

      <Button type="button" variant="outline" size="sm" onClick={addRow} className="gap-1.5">
        <Plus className="w-4 h-4" />
        Añadir
      </Button>
    </div>
  );
};

export default DynamicFieldArray;
