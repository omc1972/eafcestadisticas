import React from "react";
import type { FieldConfig } from "./DynamicForm";
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { Plus, PlusCircle, PlusSquare } from "lucide-react";

interface DynamicFieldArrayProps {
  field: FieldConfig;
  data: any[];
  setData: (name: string, value: any) => void;
  errors: Record<string, any>;
}

const DynamicFieldArray: React.FC<DynamicFieldArrayProps> = ({ field, data, setData, errors }) => {
  const parseSelectValue = (config: FieldConfig, value: string) => {
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
        <div key={index} className="flex flex-wrap gap-2 items-end">
          {field.fields?.map((f) => (
            // skip rendering hidden fields (they still exist in row data)
            f.hidden ? null : (
            <div key={f.name} className="flex-1 min-w-[120px]">
              {f.type === "boolean" ? (
                <Checkbox
                  checked={!!row[f.name]}
                  onCheckedChange={(checked) => handleChange(index, f.name, checked)}
                  disabled={Boolean((f as any).disabled)}
                >
                  {f.label}
                </Checkbox>
              ) : f.type === "select" ? (
                <Select
                  value={row[f.name] ? String(row[f.name]) : ""}
                  onValueChange={(value) => handleChange(index, f.name, parseSelectValue(f, value))}
                  disabled={Boolean((f as any).disabled)}
                >
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Seleccione..." />
                  </SelectTrigger>
                  <SelectContent>
                    {f.options?.map((opt) => (
                      <SelectItem key={opt.value} value={String(opt.value)}>
                        {opt.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              ) : (
                <Input
                  type={f.type}
                  value={row[f.name]}
                  onChange={(e) => handleChange(index, f.name, e.target.value)}
                  placeholder={f.label}
                  disabled={Boolean((f as any).disabled)}
                />
              )}

              {errors?.[field.name]?.[index]?.[f.name] && (
                <p className="text-red-500 text-sm mt-1">
                  {errors[field.name][index][f.name]}
                </p>
              )}
            </div>
            )
          ))}

          <Button
            type="button"
            variant="destructive"
            size="sm"
            onClick={() => removeRow(index)}
          >
            X
          </Button>
        </div>
      ))}

      <Button type="button" variant="outline" size="sm" onClick={addRow}>
        <Plus className="w-4 h-4" />
      </Button>
    </div>
  );
};

export default DynamicFieldArray;
