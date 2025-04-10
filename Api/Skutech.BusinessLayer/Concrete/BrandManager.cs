using Skutech.BusinessLayer.Abstract;
using Skutech.DataAccessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.BusinessLayer.Concrete;

public class BrandManager : IBrandService
{
    private readonly IBrand _brand;

    public BrandManager(IBrand brand)
    {
        _brand = brand;
    }

    public void TInsert(Brand entity)
    {
        _brand.Insert(entity);
    }

    public void TUpdate(Brand entity)
    {
        _brand.Update(entity);
    }

    public void TDelete(Brand entity)
    {
        _brand.Delete(entity);
    }

    public Brand? TGetElementById(int id)
    {
        return _brand.GetElementById(id);
    }

    public List<Brand> TGetList()
    {
        return _brand.GetList();
    }
}