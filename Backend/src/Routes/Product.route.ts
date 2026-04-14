import { Router, Request, Response } from "express";
import { getAllProducts } from "../models/Products";


const ProductRouter = Router();


ProductRouter.get("/", async (req: Request, res: Response) => {
    const products = await getAllProducts();
    res.status(200).json(products);
});



export default ProductRouter;