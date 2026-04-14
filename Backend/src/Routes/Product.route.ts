import { Router, Request, Response } from "express";
import { deleteProduct, getAllProducts, getProductByID, updateProduct } from "../models/Products";


const ProductRouter = Router();


ProductRouter.get("/", async (req: Request, res: Response) => {
    const products = await getAllProducts();
    res.status(200).json(products);
});


ProductRouter.get("/:id", async (req: Request, res: Response) => {
    const ProductId = parseInt(req.params.id as string);
    const Product = await getProductByID(ProductId);

    if (Product) {
        res.status(200).json({Product});
    } else {
        return res.status(404).json({message: "Product Not Found"});
    }
});


ProductRouter.put("/:id", async (req: Request, res: Response) => {
    const ProductId = parseInt(req.params.id as string);
    const updateData = req.body;

    try {
        const updatedProduct = await updateProduct(ProductId, updateData);
        if (!updateProduct) {
            return res.status(404).json({message: "Product Not Found"});
        }

        res.status(200).json(updatedProduct);
    } catch (error) {
        res.status(500).json({message: "Something When Wrong" });
    }
});

ProductRouter.delete("/:id", async (req: Request, res: Response) => {
    const prductId = parseInt(req.params.id as string);
    await deleteProduct(prductId);
    res.status(204).send();
});





export default ProductRouter;