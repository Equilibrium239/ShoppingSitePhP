import express, { Express, Request, Response } from "express";
import swaggerJsDoc from "swagger-jsdoc";
import swaggerUi from "swagger-ui-express";
import cors from "cors";
import http from 'http';
import ProductRouter from "./Routes/Product.route";

const app: Express = express();
const port = process.env.PORT || 3000;
app.use(cors());



app.use("/api/items", ProductRouter);

app.get ("/", (req: Request, res: Response) => {
    res.send("Hello World!");
});


const swaggerOptions = {
  swaggerDefinition: {
    openapi: '3.0.0',
    info: {
      title: 'My API',
      version: '1.0.0',
      description: 'API documentation',
    },
    servers: [
      {
        url: 'http://localhost:3000',
      },
    ],
  },
  apis: ['./src/Routes/*.ts'], // files containing annotations as above
};
 
const swaggerDocs = swaggerJsDoc(swaggerOptions);
app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerDocs));


//Denna app.listen() SKA LIGGA SIST PÅ FILEN dvs Längst ner på filen
app.listen(port, async () => {
    console.log(`[server]: Server is running at http://localhost:${port} `)
});
