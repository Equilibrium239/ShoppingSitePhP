import { RowDataPacket } from "mysql2";
import { connection } from "./db";


export interface Product extends RowDataPacket {
    id: number;
    name: string;
    size: string;
    img: string;
    price: number;

}

export async function getAllProducts() : Promise<Product[]> {
    let conn = await connection;
    const [rows] = await conn.query<Product[]>("SELECT * FROM Shopping.Inventory;");
    return rows;
}

export async function getProductByID(id: number) : Promise<Product | null>{
    let conn = await connection;
    const [rows] = await conn.query<Product[]>("SELECT * FROM Shopping.Inventory WHERE id = ?;", [id]);
    return rows[0] || null;
}

export async function updateProduct(
    id: number,
    Product: {name: string; size: string; img: string; price: number}
): Promise<Product | null> {
    let conn = await connection;
    await conn.query
    ("UPDATE Cloths.Inventory SET name = ?, size = ?, img = ?, price = ? WHERE id = ?;", [Product.name, Product.size, Product.img, Product.price, id]);
    return await getProductByID(id);
}