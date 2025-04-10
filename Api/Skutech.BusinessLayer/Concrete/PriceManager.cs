using Skutech.BusinessLayer.Abstract;
using Skutech.DataAccessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.BusinessLayer.Concrete;

public class PriceManager : IGenericService<Price>
{
    private readonly IPrice _price;

    public PriceManager(IPrice price)
    {
        _price = price;
    }

    public void TInsert(Price entity)
    {
        _price.Insert(entity);
    }

    public void TUpdate(Price entity)
    {
        _price.Update(entity);
    }

    public void TDelete(Price entity)
    {
        _price.Delete(entity);
    }

    public Price? TGetElementById(int id)
    {
        return _price.GetElementById(id);
    }

    public List<Price> TGetList()
    {
        return _price.GetList();
    }
}