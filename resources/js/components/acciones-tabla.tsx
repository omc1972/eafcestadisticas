// components/table-actions.tsx
import React from "react";
import { router } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { Eye, Pencil, Trash } from "lucide-react";

interface AccionesTablaProps {
  id: number;
  basePath: string;
  onView?: () => void;
  onEdit?: () => void;
  onDelete?: () => void;
  showView?: boolean;
  showEdit?: boolean;
  showDelete?: boolean;
}

const AccionesTabla: React.FC<AccionesTablaProps> = ({
  id,
  basePath,
  onView,
  onEdit,
  onDelete,
  showView = true,
  showEdit = true,
  showDelete = true
}) => {
  const handleView = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onView) {
      onView();
    } else {
      router.get(`${basePath}/${id}`);
    }
  };

  const handleEdit = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onEdit) {
      onEdit();
    } else {
      router.get(`${basePath}/${id}/edit`);
    }
  };

  const handleDelete = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onDelete) {
      onDelete();
    } else if (confirm("¿Estás seguro de que deseas eliminar este elemento?")) {
      router.delete(`${basePath}/${id}`);
    }
  };

  return (
    <div className="flex gap-1 justify-end">
      {/* Botón Ver */}
      {showEdit && (
        <Button
          variant="secondary"
          size="sm"
          className="flex items-center gap-1"
          onClick={handleView}
        >
          <Eye size={16} />
        </Button>
      )}

      {/* Botón Editar */}
      {showEdit && (
        <Button
          variant="outline"
          size="sm"
          className="flex items-center gap-1"
          onClick={handleEdit}
        >
          <Pencil size={16} />
        </Button>
      )}

      {/* Botón Eliminar */}
      {showEdit && (
        <Button
          variant="destructive"
          size="sm"
          className="flex items-center gap-1"
          onClick={handleDelete}
        >
          <Trash size={16} />
        </Button>
      )}
    </div>
  );
};

export default AccionesTabla;