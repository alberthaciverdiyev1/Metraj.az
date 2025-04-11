using Skutech.BusinessLayer.Abstract;
using Skutech.DataAccessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.BusinessLayer.Concrete;

public class ColorManager : IColorService
{
    private readonly IColor _color;

    public ColorManager(IColor color)
    {
        _color = color;
    }

    public void TInsert(Color entity)
    {
        _color.Insert(entity);
    }

    public void TUpdate(Color entity)
    {
        _color.Update(entity);
    }

    public void TDelete(Color entity)
    {
        _color.Delete(entity);
    }

    public Color? TGetElementById(int id)
    {
        return _color.GetElementById(id);
    }

    public List<Color> TGetList()
    {
        return _color.GetList();
    }
}