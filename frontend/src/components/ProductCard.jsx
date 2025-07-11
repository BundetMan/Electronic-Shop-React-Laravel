import { Link } from "react-router-dom";

const ProductCard = ({ product }) => {
  // Fallbacks for missing data
  const price = Number(product.price) || 0; // Ensure price is a number
  const discount = typeof product.discount === "number" ? product.discount : 0;
  const rate = typeof product.rate === "number" ? product.rate : 0;
  const imageUrl =
    product.images && product.images.length > 0
      ? product.images[0]?.url
      : "/placeholder.png";

  // Calculate discounted price
  const discountedPrice = price - (price * discount) / 100;

  return (
    <div className="group rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-lg transition-shadow">
      <div className="p-4">
        <div className="relative mb-4">
          <img
            src={imageUrl}
            alt={product.name || "Product"}
            className="group-hover:scale-105 transition-all duration-300 w-full aspect-square object-cover rounded-lg mix-blend-multiply"
          />
          {discount > 0 && (
            <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold  border-transparent hover:bg-primary/80 absolute top-2 left-2 bg-blue-600 text-white">
              {discount}% OFF
            </div>
          )}
        </div>
        <h3 className="font-semibold text-gray-900 mb-2 line-clamp-1">
          {product.name}
        </h3>
        <div className="flex items-center gap-2">
          <div className="flex text-[14px] text-yellow-500">
            {Array.from({ length: 5 }).map((_, idx) => {
              if (rate >= idx + 1) {
                return <i key={idx} className="bx bxs-star"></i>;
              } else if (rate >= idx + 0.5) {
                return <i key={idx} className="bx bxs-star-half"></i>;
              } else {
                return <i key={idx} className="bx bx-star"></i>;
              }
            })}
          </div>
          <span className="text-[11px]">
            ${price.toFixed(2)}
          </span>
        </div>
        <div className="flex items-center justify-between">
          <div>
            {discount > 0 ? (
              <>
                <span className="text-sm font-bold text-gray-900">
                  ${discountedPrice.toFixed(2)}
                </span>
                <span className="text-[11px] text-gray-500 line-through ml-2">
                  ${price.toFixed(2)}
                </span>
              </>
            ) : (
              <span className="text-lg font-bold text-gray-900">
                ${price.toFixed(2)}
              </span>
            )}
          </div>
          <Link
            to={`/products/${product.id}`}
            className="inline-flex items-center justify-center gap-2 whitespace-nowrap text-[12px] bg-gray-900 font-medium text-primary-foreground hover:bg-primary/90 text-white py-2 rounded-md px-3"
          >
            View Product
          </Link>
        </div>
      </div>
    </div>
  );
};

export default ProductCard;