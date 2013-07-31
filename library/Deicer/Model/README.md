Model Component
===============
A pair of abstract classes to serve as the base for your Data Transfer Objects or models in your application.
Classes support array hydration and extraction to allow easy initialization and data transfer.
Simply extend the two abstract classes below to provide a consistent, Composite Pattern API for your models.

- Deicer\Model\AbstractModel - Base class for DTOs and models.
- Deicer\Model\AbstractModelComposite - An iterable, composite set of models.

See the test fixture implementations located under the DeicerTest\Model namespace for example concrete implementations of the above.

---------------------------------------------------
Copyright (c) 2013 Alex Butucea <alex826@gmail.com>
