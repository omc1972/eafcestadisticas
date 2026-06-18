import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import DynamicFieldArray from "./DynamicFieldArray";
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import { Select as ShadSelect, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";

export type FieldType =
  | "text"
  | "email"
  | "password"
  | "number"
  | "select"
  | "boolean"
  | "dynamicArray"
  | "multiCheckbox"; // ✅ nuevo tipo para checkboxes múltiples

export interface FieldConfig {
  name: string;
  label: string;
  type: FieldType;
  options?: { value: string | number; label: string }[];
  default?: any;
  fields?: FieldConfig[];
  hidden?: boolean;
}

interface DynamicFormProps {
  fields: FieldConfig[];
  method?: "post" | "put" | "patch";
  action: string;
  cancel: string;
  initialValues?: Record<string, any>;
  onSuccess?: () => void;
}

const DynamicForm: React.FC<DynamicFormProps> = ({
  fields,
  method = "post",
  action,
  cancel,
  initialValues = {},
  onSuccess,
}) => {
  const { data, setData, errors, processing, post, put, patch } = useForm(initialValues);

  const getSelectValue = (value: any) => {
    if (value === null || value === undefined) return "";
    return String(value);
  };

  const parseSelectValue = (field: FieldConfig, value: string) => {
    const matchedOption = field.options?.find((opt) => String(opt.value) === value);
    if (matchedOption && typeof matchedOption.value === "number") {
      const parsed = Number(value);
      return Number.isNaN(parsed) ? value : parsed;
    }
    return value;
  };

  useEffect(() => {
    setData(initialValues);
  }, [JSON.stringify(initialValues)]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const submit = { post, put, patch }[method];
    if (submit) submit(action, { onSuccess });
  };

  return (
    <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-4">
      {fields.map((field) => {
        const renderField = () => {
          if (field.type === "dynamicArray") {
            return (
              <div key={field.name} className="col-span-1 md:col-span-2">
                <DynamicFieldArray
                  field={field}
                  data={data[field.name] ?? []}
                  setData={setData}
                  errors={errors}
                />
              </div>
            );
          }

          if (field.type === "multiCheckbox") {
            return (
              <div key={field.name} className="col-span-1 md:col-span-2">
                <label className="block font-medium mb-1">{field.label}</label>
                <div className="flex flex-wrap gap-4">
                  {field.options?.map((opt) => (
                    <div key={opt.value} className="flex items-center space-x-2">
                      <Checkbox
                        id={`${field.name}-${opt.value}`}
                        checked={(data[field.name] ?? []).includes(opt.value)}
                        onCheckedChange={(checked) => {
                          const current: any[] = data[field.name] ?? [];
                          if (checked) setData(field.name, [...current, opt.value]);
                          else setData(field.name, current.filter((v) => v !== opt.value));
                        }}
                      />
                      <label htmlFor={`${field.name}-${opt.value}`} className="text-sm">
                        {opt.label}
                      </label>
                    </div>
                  ))}
                </div>
                {errors[field.name] && <p className="text-red-500 text-sm mt-1">{errors[field.name]}</p>}
              </div>
            );
          }

          if (field.type === "boolean") {
            return (
              <div key={field.name}>
                <Checkbox
                  checked={!!data[field.name]}
                  onCheckedChange={(checked) => setData(field.name, checked)}
                  disabled={Boolean((field as any).disabled)}
                >
                  {field.label}
                </Checkbox>
                {errors[field.name] && <p className="text-red-500 text-sm mt-1">{errors[field.name]}</p>}
              </div>
            );
          }

          if (field.type === "select") {
            return (
              <div key={field.name}>
                <label className="block font-medium mb-1">{field.label}</label>
                <ShadSelect
                  value={getSelectValue(data[field.name])}
                  onValueChange={(value) => setData(field.name, parseSelectValue(field, value))}
                  disabled={Boolean((field as any).disabled)}
                >
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Seleccione..." />
                  </SelectTrigger>
                  <SelectContent>
                    {field.options?.map((opt) => (
                      <SelectItem key={opt.value} value={String(opt.value)}>
                        {opt.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </ShadSelect>
                {errors[field.name] && <p className="text-red-500 text-sm mt-1">{errors[field.name]}</p>}
              </div>
            );
          }

          // default input
          return (
            <div key={field.name}>
              <label className="block font-medium mb-1">{field.label}</label>
              <Input
                type={field.type}
                value={data[field.name] ?? ""}
                onChange={(e) => setData(field.name, e.target.value)}
                disabled={Boolean((field as any).disabled)}
              />
              {errors[field.name] && <p className="text-red-500 text-sm mt-1">{errors[field.name]}</p>}
            </div>
          );
        };

        return renderField();
      })}

      <div className="col-span-1 md:col-span-2 flex justify-end space-x-2 mt-4">
        <Button variant="secondary" type="button" onClick={() => window.location.href = cancel}>
          Cancelar
        </Button>
        <Button type="submit" disabled={processing}>
          {processing ? "Enviando..." : "Guardar"}
        </Button>
      </div>
    </form>
  );
};

export default DynamicForm;
