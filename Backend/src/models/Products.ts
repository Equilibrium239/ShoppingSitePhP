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