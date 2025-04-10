namespace Skutech.BusinessLayer.Abstract;

public interface IGenericService<T> where T : class
{
    void TInsert(T entity);
    void TUpdate(T entity);
    void TDelete(T entity);
    T? TGetElementById(int id);
    List<T> TGetList();
}