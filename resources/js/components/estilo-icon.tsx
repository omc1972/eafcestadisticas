import React from "react";

interface EstiloIconProps {
  estilo: {
    nombre: string;
    nivel: "Oro" | "Plata";
  };
}

const EstiloIcon: React.FC<EstiloIconProps> = ({ estilo }) => {
  const iconName = estilo.nombre.toLowerCase().replace(/\s+/g, "_");
  const bgColor = estilo.nivel === "Oro" ? "#FFD700" : "#C0C0C0";

  return (
    <div
      title={estilo.nombre} // muestra el nombre al hacer hover
      className="relative w-10 h-10 flex items-center justify-center"

    >
      <img
        src={`/storage/images/${iconName}.png`}
        alt={estilo.nombre}
        className="w-6 h-6 object-contain"
      />
    </div>
  );
};

export default EstiloIcon;