Model Component
===============
A collection of abstract classes to serve as the base for your domain business objects and containers in your application.
Simply extend the two abstract classes below to provide a consistent, Composite Pattern API for your value objects.

- Colt\Model\AbstractModel - Provides a common extension point for all domain models.
- Colt\Model\AbstractModelComposite - Represents a composite set of domain specific models.

See the test fixture implementations located under the ColtTest\Model namespace for example concrete implementations of the above.

---------------------------------------------------
Copyright (c) 2013 Alex Butucea <alex826@gmail.com>

